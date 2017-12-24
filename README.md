# shared-media-gallery v0.1

Shared Media Gallery

## Build

Create new database:
~~~

# Shared Media ORM
vendor/bin/propel sql:insert \
    --platform=sqlite \
    --connection='default=sqlite:database/gallery.sq3' \
    --sql-dir=vendor/attogram/shared-media-orm/config/ \
    --config-dir=vendor/attogram/shared-media-orm/config/
	
# Gallery site
vendor/bin/propel sql:insert

~~~
