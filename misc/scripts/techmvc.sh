#******************************************************************************************   

#  Package            : TechMVC Installer
#  Version            : 0.1
      
#  Lead Architect     : Sougata Pal. [ skall.paul@techunits.com ]     
#  Year               : 2011                                                               

#  Site               : http://www.techunits.com/ OR http://www.techmvc.com/
#  Contact / Support  : techmvc@googlegroups.com

#  Copyright (C) 2009 - 2011 by TECHUNITS

#  Permission is hereby granted, free of charge, to any person obtaining a copy
#  of this software and associated documentation files (the "Software"), to deal
#  in the Software without restriction, including without limitation the rights
#  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
#  copies of the Software, and to permit persons to whom the Software is
#  furnished to do so, subject to the following conditions:

#  The above copyright notice and this permission notice shall be included in
#  all copies or substantial portions of the Software.

#  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
#  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
#  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
#  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
#  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
#  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
#  THE SOFTWARE.
  
#******************************************************************************************   

loggedinUser=`whoami`
if [ "root" = "$loggedinUser" ]
then 
  echo "User Access Verified"
else 
  echo "Need root Access"
  exit
fi

hash httpd 2>&- || { echo >&2 "TechMVC require Apache but it's not installed.  Trying to install..."; yum install httpd -y; }

hash php 2>&- || { echo >&2 "TechMVC require PHP but it's not installed.  Trying to install..."; yum install php -y; }

hash wget 2>&- || { echo >&2 "TechMVC require Wget but it's not installed.  Trying to install..."; yum install wget -y; }

# Download TechMVC Latest Version
echo "Downloading TechMVC..."
mkdir /tmp/TechMVC -p
cd /tmp/TechMVC
wget -c http://mirror.techmvc.techunits.com/latest.tar.gz
cd -

# Setup Project Name
read -p "Project Name (e.g. yoursite) [TechMVC]: " ProjectName
if [ "" = "$ProjectName" ]
then
  ProjectName="TechMVC"
fi

# Setup Project Root Path
read -p "Web Server RootPath[/var/www/html] : " rootPath
if [ "" = "$rootPath" ]
then
  rootPath="/var/www/html"
fi

echo "Installing TechMVC PHP Library..."
echo "Target Path: "$rootPath"/"$ProjectName
mkdir $rootPath"/"$ProjectName -p
cd /tmp/TechMVC
tar -xzf /tmp/TechMVC/latest.tar.gz
mv /tmp/TechMVC/TechMVC/* $rootPath"/"$ProjectName
cd -

echo "Removing Temp config & files..."
rm -rf /tmp/TechMVC
echo
echo "Note: TechMVC basic dependencies has been installed. If you require extra plugins(e.g. php-mysql, php-mongo) to be installed, please use linux guide to install them. Alternatively you can post to our community, we will get back to you ASAP."
echo 
echo "TechMVC installed Successfully."
echo
echo "TestURL: http://localhost/"$ProjectName
echo 
