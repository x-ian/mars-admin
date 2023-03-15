#!/bin/bash

PANDOC_INSTALLED=$(pandoc --version >> /dev/null; echo $?)

if [ "0" == ${PANDOC_INSTALLED} ]; then
    cp "$1" "$2"
    pandoc -o "$2" -f markdown -t docx "$2"
else
    echo "Pandoc is not installed. Unable to convert document."
fi

