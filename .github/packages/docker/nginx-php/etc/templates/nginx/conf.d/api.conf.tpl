server {
	listen   ${WEB_PORT};

	location /.api/ {
		stub_status on;
		keepalive_timeout 0;
		access_log   off;
	}

}
