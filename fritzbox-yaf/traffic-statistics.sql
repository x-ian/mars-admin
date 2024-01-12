select 
mac,
ip,
hostname,
-- SUM(CASE WHEN period = "last 2 hours" THEN Download ELSE 0 END) as last2hours_down,
-- SUM(CASE WHEN period = "last 2 hours" THEN Upload ELSE 0 END) as last2hours_up,
SUM(CASE WHEN period = "today" THEN Download ELSE 0 END) as today_down,
SUM(CASE WHEN period = "today" THEN Upload ELSE 0 END) as today_up,
SUM(CASE WHEN period = "yesterday" THEN Download ELSE 0 END) as yesterday_down,
SUM(CASE WHEN period = "yesterday" THEN Upload ELSE 0 END) as yesterday_up,
SUM(CASE WHEN period = "last 7 days" THEN Download ELSE 0 END) as lastweek_down,
SUM(CASE WHEN period = "last 7 days" THEN Upload ELSE 0 END) as lastweek_up,
SUM(CASE WHEN period = "last 30 days" THEN Download ELSE 0 END) as lastmonth_down,
SUM(CASE WHEN period = "last 30 days" THEN Upload ELSE 0 END) as lastmonth_up

from 

(
	
-- select '2 hours ago' as period, traffic_details_full.mac, hostname, local_hosts.ip, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download
-- from traffic_details_full
-- left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip
-- left join local_hosts on traffic_details_full.mac = local_hosts.mac
-- where DATE(ts)=CURDATE()
-- group by mac
--
-- UNION


select 'today' as period, traffic_details_full.mac, hostname, local_hosts.ip, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download  
from traffic_details_full   
left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip   
left join local_hosts on traffic_details_full.mac = local_hosts.mac 
where DATE(ts)=CURDATE()
group by mac  

UNION

select 'yesterday' as period, traffic_details_full.mac, hostname, local_hosts.ip, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download  
from traffic_details_full   
left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip   
left join local_hosts on traffic_details_full.mac = local_hosts.mac 
where DATE(ts)=subdate(curdate(), 1)
group by mac  

UNION

select 'last 7 days' as period, traffic_details_full.mac, hostname, local_hosts.ip, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download  
from traffic_details_full   
left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip   
left join local_hosts on traffic_details_full.mac = local_hosts.mac 
where DATE(ts)>subdate(curdate(), 7)
group by mac  

UNION

select 'last 30 days' as period, traffic_details_full.mac, hostname, local_hosts.ip, reverse_dns, ROUND(sum(outgoing) / 1000000) as Upload, ROUND(sum(incoming) / 1000000) as Download  
from traffic_details_full   
left join ip_registry on traffic_details_full.remote_ip = ip_registry.ip   
left join local_hosts on traffic_details_full.mac = local_hosts.mac 
where DATE(ts)>subdate(curdate(), 30)
group by mac  
) a

where download > 0 and upload > 0
group by  mac
order by today_down DESC;

