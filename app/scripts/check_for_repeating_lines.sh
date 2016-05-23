if [ -z $1 ]; then
	echo "please provide a filename"
fi

sort /global/app/logs/$1 | uniq -c | awk '{ if($1 != 1) {print $1}}'
