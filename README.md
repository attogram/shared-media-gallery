# shared-media-gallery v0.0

Web Gallery for attogram/shared-media-orm

## Build

Create new database:
~~~
vendor/bin/propel sql:insert \
    --platform=sqlite \
    --connection='default=sqlite:database/gallery.sq3' \
    --sql-dir=vendor/attogram/shared-media-orm/config/ \
    --config-dir=vendor/attogram/shared-media-orm/config/
~~~
