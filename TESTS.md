In order to successfully run the unit tests, use either the recommended Docker workflow with a running `ck-web` container, or the manual host-PHP workflow with the Credit Key API source installed locally and your local development copy of the API (puma) running.

**PHP Requirement:** Running the test suite requires PHP 8.3 or higher (imposed by `phpunit/phpunit ^12.5`). The SDK runtime itself supports PHP 5.6+.

## Recommended: ck-ecosystem (Docker)

If you run ck-web via [ck-ecosystem](https://github.com/creditkey/ck-ecosystem), use the wrapper script. It builds a small image (defined in `Dockerfile.test`) containing PHP 8.3, composer, the docker CLI, and a `bundle` shim that proxies `bundle exec rake` calls into the ck-web container, then runs the suite with the test container sharing ck-web's network namespace:

```
% ./bin/test
```

Pass extra arguments through to phpunit:

```
% ./bin/test --filter testAuthenticate
```

The first run builds the image (~1 min); subsequent runs reuse Docker's layer cache and are nearly instant. By default the script targets the container named `ck-web-ck-web-1`. Override with `CK_WEB_CONTAINER=<name> ./bin/test` if your container is named differently.

## Manual (host PHP)

If you have PHP 8.3+ and the Credit Key API installed directly on your host:

Set the path to the API source using the environment variable ```CREDITKEY_SOURCE_PATH```.  This path is used to run rake tasks from that directory.

Run the unit tests from the command-line:

```
% ./vendor/bin/phpunit
```
