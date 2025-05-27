Pagination
==========
A set of classes which compliment and extend the PHP PDO library to provide support for pagination.

[![Build Status](https://travis-ci.org/SoampliApps/Pagination.png)](https://travis-ci.org/SoampliApps/Pagination) [![Total Downloads](https://poser.pugx.org/soampliapps/pagination/d/total.png)](https://packagist.org/packages/soampliapps/pagination)

This is a beta release, and subject to change, however it is functional.

TODO: Investigate using `\PDO::ATTR_STATEMENT_CLASS` in prepare method calls instead to reduce the complexity of the project.

## Installation (with composer)

Add

    "soampliapps/pagination": "v1.0.0"

To your `composer.json` file, then install

    php composer.phar update

## Usage

Create a pagination object to represent the current page

    $pagination = new \SoampliApps\Pagination\Pagination();
    $pagination->setMaxResultsPerPage(25);
    $pagination->setCurrentPageNumber(1);

Instantiate the extended Pdo class

```$pdo = new \SoampliApps\Pagination\Pdo(...)```

If you already have an extended Pdo class, the functionality is available within the PdoTrait class; though this is likely to change as functionality is migrated into the statement.

Call prepareWithPagination, passing the pagination object as the second parameter (driver options is available as the third parameter)

```$statement = $pdo->prepareWithPagination($sql, $pagination);```

The resulting statement is special. It contains within it, the statement for the paginated query, and a statement which counts the total number of applicable records (to calculate page numbers). Bindings to the statement apply to both statements, and upon execution, both statements are executed, the resulting page number stored within itself. Calling `getPaginationWithTotalCount` on the statement post-execution returns the original pagination object, with total number of results. This can be used to generate a pagination nav-bar.
