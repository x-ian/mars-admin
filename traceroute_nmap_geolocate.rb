#!/usr/bin/env ruby

# nmap vs traceroute
# sudo
# nmap script traceroute-geolocation.nse  (http://nmap.org/nsedoc/scripts/traceroute-geolocation.html)
# Open Visual Trace Route (http://sourceforge.net/projects/openvisualtrace)
# geoplugin.net and http://www.geoplugin.com/webservices/json

require 'open-uri'
require 'JSON'

  host = ARGV[0]
  
  print "Be patient. nmap needs to do its job first (can take serveral minutes)"
  pipe = IO.popen("nmap -sP --traceroute #{host}")
  while (line = pipe.gets)
    elements = line.split(" ")
    if line =~ /\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/
      # replace ip address with ip address and country      
	    new_line = line.sub %r{(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})} do |full_match|
	      ip = "" + $1
        geoplugin = URI.parse("http://www.geoplugin.net/json.gp?ip=#{ip}").read
        parsed = JSON.parse(geoplugin)
        country = parsed["geoplugin_countryName"]
        city = parsed["geoplugin_city"]
	      "#{ip} [#{city}, #{country}]"
	    end
	    print new_line
	  else
	    print line
    end
  end

