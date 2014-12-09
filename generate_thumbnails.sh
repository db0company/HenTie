#!/bin/bash
## ########################################################################## ##
## Project: HenTie                                                            ##
## Description: Script to generate thumbnails for images displayed in HenTie  ##
## Author: db0 (db0company@gmail.com, http://db0.fr/)                         ##
## Latest Version is on GitHub: https://github.com/db0company/HenTie          ##
## Note: This is a copy of a script from Gallery on my GitHub                 ##
## ########################################################################## ##

## ########################################################################## ##
## Is ImageMagick installed?                                                  ##
## ########################################################################## ##

type convert > /dev/null
if [ $? -ne 0 ]
then
    echo >&2 "ImageMagick is not installed!"
    exit 1
fi

if [ -e `pwd`/squareup.sh ]
then
    convert=`pwd`'/squareup.sh -s 200x200 -m crop '
else
    echo 'Note: For better results, download the squareup script here: http://www.fmwconcepts.com/imagemagick/squareup/index.php'
    convert='convert -resize 200x200 '
fi

## ########################################################################## ##
## Command line arguments checking                                            ##
## ########################################################################## ##

function usage() {
    echo >&2 "usage: $0 images_directory [--clean]";
}

if [ $# -lt 1 ]||[ $# -gt 2 ]
then
    usage
    exit 1
fi

if [ ! -d $1 ]
then
    echo "$1: No such file or directory."
    exit 2
fi

## ########################################################################## ##
## Clean directory: Remove thumbnails!                                        ##
## ########################################################################## ##

# This function remove all the thumbnails previously generated                ##
function	clean() {
    echo "Deleting thumbnails..." && \
	find $1 -name ".thb_*" -print -and -delete && \
	echo "Done."
}

if [ $# -eq 2 ]&&[ $2 = '--clean' ]
then
    clean $1
    exit 0
fi

## ########################################################################## ##
## Generate thumbnails                                                        ##
## ########################################################################## ##

# Array of string containing allowed extensions for images files              ##
declare -a allowed_extension=("jpeg" "jpg" "png" "gif" "bmp")

# generate_thumbnail take a string filename, check if it's an image using the ##
# extension and generate a thumbnail for this image using imagemagick         ##
function generate_thumbnail() {
    filename=$1
    if [ $filename = '*' ]
    then return;
    fi
    for i in ${!allowed_extension[*]}
    do
	if [ ${filename##*.} = ${allowed_extension[i]} ]
	then
	    echo -n "file $filename, conversion..."
	    thb=".thb_$filename"
	    $convert $filename $thb && \
		echo "Done."
	    return;
	fi
    done
    echo "file $filename, extension not allowed"
}

## generate_thumbnails browse the current directory and all subdirectories    ##
## and call generate_thumbnail for each files                                 ##
function generate_thumbnails() {
    for d in *; do
    if [ -d $d ]
    then
	cd $d
	generate_thumbnails
	cd ..
    else
	generate_thumbnail $d
    fi
  done
}

## Call generate_thumbnails with the given directory                          ##
cd $1
if [ $? -eq 0 ]
then generate_thumbnails
else exit 2
fi

## Program terminated in success                                              ##
exit 0
