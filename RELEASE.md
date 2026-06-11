# Releasing

1. Ensure the test suite passes:

       composer test

2. To quickly fix PHPCS issues:

       composer cbf

3. Tag a new release following [semantic versioning](https://semver.org/) and
   push the tag. Packagist will pick it up automatically.
