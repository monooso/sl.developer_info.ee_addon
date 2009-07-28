#!/bin/bash

# This script creates symlinks from the addon files and directories to the appropriate locations in the EE installation.
# It enables us to keep the addon in a single folder in the root of the site, and keep it version-controlled, separate
# from the main website.

addon_dir_path=`pwd`

echo "Enter the path to your ExpressionEngine installation, without a trailing slash (e.g. /var/www/html/mysite.com), and press ENTER:"
read ee_path
echo "Enter your 'system' folder name, and press ENTER:"
read ee_system_folder

# Delete any existing symlinks.
if [ -e "$ee_path"/"$ee_system_folder"/modules/sl_developer_info ]
	then
		rm -R "$ee_path"/"$ee_system_folder"/modules/sl_developer_info
fi

if [ -e "$ee_path"/"$ee_system_folder"/extensions/ext.sl_developer_info_ext.php ]
	then
		rm "$ee_path"/"$ee_system_folder"/extensions/ext.sl_developer_info_ext.php
fi

if [ -e "$ee_path"/"$ee_system_folder"/language/english/lang.sl_developer_info.php ]
	then
		rm "$ee_path"/"$ee_system_folder"/language/english/lang.sl_developer_info.php
fi

if [ -e "$ee_path"/"$ee_system_folder"/language/english/lang.sl_developer_info_ext.php ]
	then
		rm "$ee_path"/"$ee_system_folder"/language/english/lang.sl_developer_info_ext.php
fi

# Create the symlinks.
ln -s "$addon_dir_path"/system/modules/sl_developer_info "$ee_path"/"$ee_system_folder"/modules/sl_developer_info
ln -s "$addon_dir_path"/system/extensions/ext.sl_developer_info_ext.php "$ee_path"/"$ee_system_folder"/extensions/ext.sl_developer_info_ext.php
ln -s "$addon_dir_path"/system/language/english/lang.sl_developer_info.php "$ee_path"/"$ee_system_folder"/language/english/lang.sl_developer_info.php
ln -s "$addon_dir_path"/system/language/english/lang.sl_developer_info.php "$ee_path"/"$ee_system_folder"/language/english/lang.sl_developer_info_ext.php
