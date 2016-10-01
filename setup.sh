sqlite3 database.sq3 "DROP TABLE IF EXISTS posts"

sqlite3 database.sq3 "CREATE TABLE posts (
  id INTEGER PRIMARY KEY NOT NULL,
  title CHAR(50) NOT NULL,
  slug CHAR(50) NOT NULL UNIQUE,
  body TEXT NOT NULL,
  date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
);"

sqlite3 database.sq3 "insert into posts (title, slug, body) values ('hello', 'hello', 'hehkfehsk ufhseiu hfiseh ifhsei');"
sqlite3 database.sq3 "insert into posts (title, slug, body) values ('howdy', 'howdy', 'weifiw ehgfihwe ewfi hwefihew');"
