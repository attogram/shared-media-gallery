# shared-media-gallery

Web Gallery for attogram/shared-media-orm

## Build

~~~
vendor/bin/propel sql:insert \
    --sql-dir=vendor/attogram/shared-media-orm/config/ \
    --platform=sqlite \
    --connection='default=sqlite:database/gallery.sq3' \
    --config-dir=vendor/attogram/shared-media-orm/config/
~~~
