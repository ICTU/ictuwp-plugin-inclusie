# shortcode: 'inclusie'
# sh '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/development/wp-content/plugins/ictu-gc-posttypes-inclusie/distribute.sh' &>/dev/null

echo '-- JA --';
echo '----------------------------------------------------------------';
echo 'Distribute GC post type plugin';

# clear the log file
> '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/development/wp-content/debug.log'

# copy to temp dir
# rsync -r -a --delete '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/development/wp-content/plugins/ictu-gc-posttypes-inclusie/' '/Users/paul/shared-paul-files/Webs/temp/'
rsync -r -a --delete '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/development/wp-content/plugins/ictu-gc-posttypes-inclusie/' '/Users/paul/shared-paul-files/Webs/temp/'

# clean up temp dir
rm -rf '/Users/paul/shared-paul-files/Webs/temp/.codekit-cache/'
rm -rf '/Users/paul/shared-paul-files/Webs/temp/.git/'
rm -rf '/Users/paul/shared-paul-files/Webs/temp/.idea/'
rm -rf '/Users/paul/shared-paul-files/Webs/temp/node_modules/'
rm -rf '/Users/paul/shared-paul-files/Webs/temp/frontend/'


rm '/Users/paul/shared-paul-files/Webs/temp/config.codekit'
rm '/Users/paul/shared-paul-files/Webs/temp/config.codekit3'
rm '/Users/paul/shared-paul-files/Webs/temp/distribute.sh'
rm '/Users/paul/shared-paul-files/Webs/temp/readme.txt'
rm '/Users/paul/shared-paul-files/Webs/temp/.config.codekit3'
rm '/Users/paul/shared-paul-files/Webs/temp/.gitignore'
rm '/Users/paul/shared-paul-files/Webs/temp/.idea'
rm '/Users/paul/shared-paul-files/Webs/temp/gebruikercentraal-wp-theme.code-workspace'
rm '/Users/paul/shared-paul-files/Webs/temp/yarn.lock'
rm '/Users/paul/shared-paul-files/Webs/temp/gulpfile.js'
rm '/Users/paul/shared-paul-files/Webs/temp/package.json'





cd '/Users/paul/shared-paul-files/Webs/temp/'
find . -name "*.code-workspace" -type f -delete
find . -name "*.DS_Store" -type f -delete
find . -name "*.map" -type f -delete


# --------------------------------------------------------------------------------------------------------------------------------
# Vertalingen --------------------------------------------------------------------------------------------------------------------
# --------------------------------------------------------------------------------------------------------------------------------
# remove the .pot
rm '/Users/paul/shared-paul-files/Webs/temp/languages/ictu-gc-posttypes-inclusie.pot'

# copy files to /wp-content/languages/themes
rsync -ah '/Users/paul/shared-paul-files/Webs/temp/languages/' '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/development/wp-content/languages/plugins/'

# languages Sentia accept
rsync -ah '/Users/paul/shared-paul-files/Webs/temp/languages/' '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/sentia/accept/www/wp-content/languages/plugins/'

# languages Sentia live
rsync -ah '/Users/paul/shared-paul-files/Webs/temp/languages/' '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/sentia/live/www/wp-content/languages/plugins/'


cd '/Users/paul/shared-paul-files/Webs/temp/'
find . -name ‘*.DS_Store’ -type f -delete

# en een kopietje naar Sentia accept
rsync -r -a --delete '/Users/paul/shared-paul-files/Webs/temp/' '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/sentia/accept/www/wp-content/plugins/ictu-gc-posttypes-inclusie/'

# en een kopietje naar Sentia live
rsync -r -a --delete '/Users/paul/shared-paul-files/Webs/temp/' '/Users/paul/shared-paul-files/Webs/ICTU/Gebruiker Centraal/sentia/live/www/wp-content/plugins/ictu-gc-posttypes-inclusie/'

# remove temp dir
rm -rf '/Users/paul/shared-paul-files/Webs/temp/'


echo 'Ready';
echo '----------------------------------------------------------------';
