#!/bin/python

import MySQLdb
# Mysql config
import settings

# Running commands
import subprocess
# Folder existence checking, and file operations
import os

# Deleting a folder
import shutil

import pprint
import glob
from subprocess import Popen, PIPE
from pprint import pprint

# Just a simple script to automate deployment of WJR instances (by Russell Porter 2011):
# Club object from MySQL query data
class Club:
    def __init__(self, data):
        self.id = int(data[0])
        self.name = data[1]
        self.actual_acronym = data[2]
        self.acronym = self.actual_acronym.lower()
        self.location = data[3]
        self.description = data[4]
        self.lat = float(data[5])
        self.lng = float(data[6])
        self.url = data[7]
        self.timezone = data[8]
        self.configuration = data[9]
        self.domain = data[10]
        
        if(self.domain == None):
            self.domain = self.acronym + ".whyjustrun.ca"
        
        if(self.configuration == None):
            self.configuration = ''
        
    def tmp_config_path(self):
        return os.path.join(self.tmp_path(), 'app/Config/branch_specifics.php')
    
    def path(self):
        return os.path.join(settings.INSTANCE_ROOT, self.acronym)
        
    def web_path(self):
        return os.path.join(self.path(), 'app/webroot')
        
    def tmp_path(self):
        return os.path.join(settings.INSTANCE_ROOT, self.acronym + "_install")
    
    def tmp_data_path(self):
        return os.path.join(settings.DATA_DIR, self.acronym)
    
    def data_path(self):
        return os.path.join(self.tmp_path(), 'app/webroot/data')
        
    def sites_available_path(self):
        return '/etc/apache2/sites-available/' + self.domain + '.conf'
    
    def sites_enabled_path(self):
        return '/etc/apache2/sites-enabled/' + self.domain + '.conf'
    
    def write_http_config(self, http_config):
        execute('rm %s' % (self.sites_enabled_path()))
        execute('rm %s' % (self.sites_available_path()))
        config = open(self.sites_available_path(), 'w')
        config.write(http_config)
        config.close()
        execute('ln -s %s %s' % (self.sites_available_path(), self.sites_enabled_path()))

def deploy_all_clubs(connection):
    cursor = connection.cursor()
    cursor.execute("SELECT id, name, acronym, location, description, lat, lng, url, timezone, configuration, custom_domain FROM clubs")
    
    data = cursor.fetchone()
    clubs = []
    while data is not None:
        club = Club(data)
        clubs.append(club)
        data = cursor.fetchone()
    
    for club in clubs:
        print 'deploying ' + club.name
        enable_maintenance_mode(club)
        deploy_club(connection, club)
        disable_maintenance_mode(club)
    
    restart_apache()
    cursor.close()

def restart_apache():
    execute('service apache2 restart')

# Deploy a club instance (decides whether to update or create the club)
def deploy_club(connection, club):
    update_http_server_conf(club)
    if(folder_exists(club.tmp_path())):
        empty_folder(club.tmp_path())
    else:
        # for the first deployment
        os.makedirs(club.tmp_path())
    
    checkout_app(club.tmp_path())
    # Modify files for club
    assemble_files(club)
    print('finished assembling files')

def enable_maintenance_mode(club):
    # first try disabling because if the deploy is killed the previous time, it will fail again if maintenance mode isn't disabled
    disable_maintenance_mode(club)
    # remove symlink
    execute('rm %s' % (club.path()))
    execute('mkdir %s' % (club.path()))
    execute('mkdir %s' % (os.path.join(club.path(), 'app')))
    execute('mkdir %s' % (os.path.join(club.path(), 'app/webroot')))
    execute('cp maintenance.html %s' % (os.path.join(club.path(), 'app/webroot/maintenance.html')))
    execute('cp maintenance.htaccess %s' % (os.path.join(club.path(), 'app/webroot/.htaccess')))

def update_http_server_conf(club):
    http_config = "\
<VirtualHost *>\n\
ServerName www." + club.domain + "\n\
DocumentRoot " + club.web_path() + "\n\
</VirtualHost>\n\
<VirtualHost *>\n\
ServerName " + club.domain + "\n\
DocumentRoot " + club.web_path() + "\n\
</VirtualHost>\n" % ()
    
    club.write_http_config(http_config)

def disable_maintenance_mode(club):
    # remove maintenance dir
    execute('rm -R %s' % (club.path()))
    # link to installation
    execute('ln -s %s %s' % (club.tmp_path(), club.path()))

def checkout_app(path):
    process = subprocess.Popen(['git', 'clone', '--quiet', settings.GIT_REPOSITORY, path], cwd=path)
    process.wait()

# Get path to a club instance folder
def get_club_path(acronym):
    return os.path.join(settings.INSTANCE_ROOT, acronym)

# Check if a club already exists
def folder_exists(path):
    return os.path.isdir(path)

# Delete folder contents
def empty_folder(path):
    shutil.rmtree(path)
    os.makedirs(path)

# Ensure a directory exists
def assure_dir(path):
    if(not folder_exists(path)):
        os.makedirs(path)

# Assemble instance specific files
def assemble_files(club):
    print('assemble files')
    
    # Move in the default configuration
    execute('cd %s && ./use_default_config.sh' % (os.path.join(club.tmp_path(), 'bin/')))
    
    # chmod 777 tmp directories
    execute('chmod -R 777 %s' % (os.path.join(club.tmp_path(), 'app/tmp/')))
    
    # chmod 777 css/js cache directories
    execute('chmod -R 777 %s' % (os.path.join(club.tmp_path(), 'app/webroot/cache/')))
    
    write_database_config(club)
    write_app_config(club)
    
    assemble_data_dirs(club)

def assemble_data_dirs(club):
    assure_dir(club.tmp_data_path())
    for data_type in settings.DATA_TYPES:
        data_type_path = os.path.join(club.tmp_data_path(), data_type)
        assure_dir(data_type_path)
    execute('chmod -R 777 %s' % club.tmp_data_path())
    execute('ln -s %s %s' % (club.tmp_data_path(), club.data_path()))

def execute(command):
    process = subprocess.Popen(command, shell=True)
    process.wait()

def write_app_config(club):
    path = club.tmp_config_path()
    print("writing config to %s" % (path))
    execute('rm %s' % (path))
    club_config = "\
<?php\n\
    Configure::write('Club.id', %s);\n\
    Configure::write('Club.name', '%s');\n\
    Configure::write('Club.acronym', '%s');\n\
    Configure::write('Club.timezone', timezone_open('%s'));\n\
    Configure::write('Club.lat', %f);\n\
    Configure::write('Club.lng', %f);\n\
    Configure::write('Club.url', '%s');\n\
    Configure::write('Club.location', '%s');\n\
?>\n" % (club.id, club.name, club.actual_acronym, club.timezone, club.lat, club.lng, club.url, club.location)
    
    config = open(path, 'w')
    config.write(club_config + club.configuration)
    config.close()

# Update club tables
def migrate_tables(connection):
    current_migration = get_migration_version(connection)
    migrations = count_migrations()
    if(current_migration == migrations):
    	print('skipping migration')
    	return

    print('migrating to ' + str(migrations) + ' from ' + str(current_migration))

    migration_cursor = connection.cursor()
    for migration in range(current_migration + 1, migrations + 1):
    	do_migration(migration)
    	set_migration_version(migration_cursor, migration)
    	
    migration_cursor.close()

def write_database_config(club):
    database_config = "<?php class DATABASE_CONFIG {\n\
    var $default = array(\n\
    'datasource' => 'Database/Mysql',\n\
    'persistent' => false,\n\
    'host' => '" + settings.MYSQL_HOST + "',\n\
    'login' => '" + settings.MYSQL_USER + "',\n\
    'password' => '" + settings.MYSQL_PASSWORD + "',\n\
    'database' => '" + settings.MYSQL_DATABASE + "',\n\
    'prefix' => '',\n\
    'encoding' => 'utf8',\n\
	);\n\
    } ?>"
    database_file = os.path.join(club.tmp_path(), 'app/Config/database.php')
    os.remove(database_file)
    f = open(database_file, 'w')
    f.write(database_config)

def set_migration_version(cursor, migration):
    cursor.execute("UPDATE `schema` SET `value` = %d WHERE `key` = 'version'" % (migration))

def get_migration_version(connection):
    cursor = connection.cursor()
    cursor.execute("SELECT value FROM `schema` WHERE `key` = 'version'")
    version = cursor.fetchone()
    cursor.close()
    return int(version[0])

def do_migration(migration):
    print('doing migration #' + str(migration))
	
    filename = 'database/migrations/' + str(migration) + '.up.sql'
    command = 'cat %s | mysql --password=%s -u %s -h %s -D %s' % (filename, settings.MYSQL_PASSWORD, settings.MYSQL_USER, settings.MYSQL_HOST, settings.MYSQL_DATABASE)
    
    process = Popen(command, shell=True)
    process.wait()

def count_files(in_directory):
    return len(glob.glob(os.path.join(in_directory,'*.up.sql')))

def count_migrations():
	return int(count_files("database/migrations"))
	
def main():
    # Database connection based on config in settings.py
    connection = MySQLdb.connect (host=settings.MYSQL_HOST, user=settings.MYSQL_USER, passwd=settings.MYSQL_PASSWORD, db=settings.MYSQL_DATABASE)
    
    print 'deploy all clubs'
    deploy_all_clubs(connection)
    
    print 'finished deploying clubs'
    
    # Commit changes above into the database
    connection.commit()
    connection.close()
    
    print 'done'


if __name__ == '__main__':
    main()
