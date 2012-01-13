#!/bin/csh
# This script removes the test database and copyies the live database into its place.

# Save existing testing database
#mysqldump -p --create-options whyjustrun_test > whyjustrun_test_old.sql

# Export the current live database
#mysqldump -p --create-options whyjustrun > whyjustrun.sql

# Reload the test database
echo "DROP DATABASE whyjustrun_test; CREATE DATABASE whyjustrun_test; USE whyjustrun_test;" > import.sql
cat whyjustrun.sql >> import.sql

mysql -p < import.sql

rm import.sql
rm whyjustrun.sql
