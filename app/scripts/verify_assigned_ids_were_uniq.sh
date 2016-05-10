if [ -z $1 ]; then
	echo "please provide a service name"
fi

sort /global/app/logs/$1.publisher.assigned_ids.log | uniq -c | awk '{ if($1 != 1) {print $1}}'
