# php-parser
 
The script parses HTML files to extract required data and saves the results in a CSV file.

## Requirements

The scirpt requires [PHP HTML Parser](https://packagist.org/packages/natlibfi/php-html-parser).

## Usage

The script parses HTML documents found in `storage` directory.
The default parsed file name is `wo_for_parse.html` but can be set to any other using a `GET` parameter `file`, e.g. `?file=file_to_be_parsed.html`.