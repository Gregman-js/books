# REST API BOOK

**Usage**
---

```
POST: http://127.0.0.1:3000/api/find
POST: http://127.0.0.1:3000/api/all
POST: http://127.0.0.1:3000/api/add
```

**Installation**
---

+ `$ docker-compose up`

###Optional
+ `$ bin/console doc:fix:load` - load mock books


Tests
---
+ `$ bin/console doc:fix:load --env=test -n`
+ `$ vendor/bin/phpunit --testdox`
