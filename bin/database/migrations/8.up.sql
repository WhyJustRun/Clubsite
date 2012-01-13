ALTER TABLE memberships ADD year YEAR;
ALTER TABLE memberships ADD created DATETIME;
ALTER TABLE events ADD is_major BOOL DEFAULT false;
