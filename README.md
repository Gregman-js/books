# REST API BOOK

**Usage**
---

```
POST: http://127.0.0.1:3000/api/find
POST: http://127.0.0.1:3000/api/all
POST: http://127.0.0.1:3000/api/add
```

API specs: `openapi/specs.yaml`



**Installation**
---

+ `$ docker-compose up`


Tests
---
+ `$ bin/console doc:fix:load --env=test -n`
+ `$ vendor/bin/phpunit --testdox`
