#!/usr/bin/env sh

# configuration
phing=vendor/bin/phing
temporaryPhing=phing.phar
repositoryUrl=https://bitbucket.org/kmelia/phing-launcher
configurationDirectory=phing
phingLauncher=phing.sh

# phing parameters
debug=false
quiet=false

phingParameters=$(echo " $@ " | sed -e "s/ /  /g" -e "s/ -\(q\(uiet\)\?\|S\|silent\) /#quiet#/g" -e "s/ -\(debug\|verbose\) /#debug#/g")
if echo $phingParameters | grep "#quiet#" > /dev/null
then
    quiet=true
fi
if echo $phingParameters | grep "#debug#" > /dev/null
then
    debug=true
fi

# functions
showMessage() {
    message=$1
    level=$2
    
    if [ -z "$level" ]
    then
        level=" info"
    fi
    
    if $quiet
    then
        if [ "$level" != "error" ]
        then
            return
        fi
    fi
    
    if [ "$level" = "debug" ]
    then
        if ! $debug
        then
            return
        fi
    fi
    
    printf "$level > $message\n"
}

showTheHelpAndExit() {
    showMessage "enjoy Phing! See the help below:\n"
    
    exec $phing
}

# read the "bin-dir" configuration setting in composer.json
composerBinDirectory=$(cat composer.json | sed 's/[" ]//g' | grep "config:" -A2 | grep "bin-dir:" | cut -d":" -f2)
if [ ! -z "$composerBinDirectory" ]
then
    showMessage "reading the "bin-dir" configuration setting in composer.json: $composerBinDirectory" "debug"
fi 

# read the COMPOSER_BIN_DIR environment variable
if [ ! -z "$COMPOSER_BIN_DIR" ]
then
    composerBinDirectory=$COMPOSER_BIN_DIR
    showMessage "reading the COMPOSER_BIN_DIR environment variable: $composerBinDirectory" "debug"
fi

if [ -d "$composerBinDirectory" ]
then
    showMessage "using composer bin directory: $composerBinDirectory instead of $(dirname $phing)"
    phing=$composerBinDirectory/$(basename $phing)
fi

# if file does not exists or file has not a size greater than zero.
if [ ! -s $phing ]
then
    if [ -e $phing ]
    then
        showMessage "removing invalid file $phing (size equals zero)"
        rm $phing
    fi
    
    if [ -s $temporaryPhing ]
    then
        if ! php $temporaryPhing > /dev/null
        then
            showMessage "removing invalid file $temporaryPhing (broken phar)"
            rm $temporaryPhing
        fi
    fi
    
    if [ ! -s $temporaryPhing ]
    then
        showMessage "downloading $temporaryPhing from origin"
        curl -sS -o $temporaryPhing http://www.phing.info/get/phing-latest.phar
        if [ ! -s $temporaryPhing ]
        then
            showMessage "Unable to download the file." "error"
            exit 1
        fi
    fi
    
    showMessage "using $temporaryPhing instead of $phing"
    phing="php $temporaryPhing"
else
    if [ ! -x $phing ]
    then
        showMessage "adding executable mode to $phing"
        chmod +x $phing
    fi
    
    if [ -f $temporaryPhing ]
    then
        showMessage "removing $temporaryPhing, phing already exists in $phing"
        rm $temporaryPhing
    fi
fi

if [ "$1" = "get-the-classics" -o "$1" = "gtc" ]
then
    showMessage "getting the classics of Phing Launcher from the repository $repositoryUrl"
    
    if [ ! -d $configurationDirectory ]
    then
        mkdir $configurationDirectory
    fi
    
    curl -sS -O $repositoryUrl/raw/master/build.xml \
        && cd $configurationDirectory \
        && curl -sS -O $repositoryUrl/raw/master/$configurationDirectory/composer.xml \
        && curl -sS -O $repositoryUrl/raw/master/$configurationDirectory/deptrac.xml \
        && curl -sS -O $repositoryUrl/raw/master/$configurationDirectory/phing.xml \
        && curl -sS -O $repositoryUrl/raw/master/$configurationDirectory/phpunit.xml \
        && curl -sS -O $repositoryUrl/raw/master/$configurationDirectory/symfony.xml \
        && cd - > /dev/null
    
    showTheHelpAndExit
fi

if [ "$1" = "self-update" -o "$1" = "selfupdate" -o "$1" = "su" ]
then
    showMessage "updating the Phing Launcher script from the repository $repositoryUrl"
    
    curl -sS -O $repositoryUrl/raw/master/$phingLauncher
    
    showTheHelpAndExit
fi

exec $phing "$@"
