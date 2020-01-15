#!/bin/bash

FB_IP=fritz.box



numHostsXML=$(curl -s -d @-     -H "Content-Type: text/xml"     -H "SOAPAction: urn:dslforum-org:service:Hosts:1#GetHostNumberOfEntries"     http://$FB_IP:49000/upnp/control/hosts <<EOF
<Envelope>
  <Body>
    <GetHostNumberOfEntries>
      <NewHostNumberOfEntries>0</NewHostNumberOfEntries>
    </GetHostNumberOfEntries>
  </Body>
</Envelope>
EOF
)

numHosts=$(grep -oPm1 "(?<=<NewHostNumberOfEntries>)[^<]+" <<< "$numHostsXML")

numHosts=$((numHosts-1))

for i in `seq 0 $numHosts`;
do

hostXML=$(curl -s -d @-     -H "Content-Type: text/xml"     -H "SOAPAction: urn:dslforum-org:service:Hosts:1#GetGenericHostEntry"     http://$FB_IP:49000/upnp/control/hosts <<EOF
<Envelope>
  <Body>
    <GetGenericHostEntry>
      <NewIndex>$i</NewIndex>
    </GetGenericHostEntry>
  </Body>
</Envelope>
EOF
)

ip=$(grep -oPm1 "(?<=<NewIPAddress>)[^<]+" <<< "$hostXML")
hostname=$(grep -oPm1 "(?<=<NewHostName>)[^<]+" <<< "$hostXML")
isActive=$(grep -oPm1 "(?<=<NewActive>)[^<]+" <<< "$hostXML")

if [[ -z $ip ]]; then
    continue
fi

echo "$ip	$hostname"
echo $hostXML
echo "-------------------------------------"
done

