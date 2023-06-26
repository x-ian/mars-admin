$From = "marsgeneralnotification@gmail.com"
$To = "cneumann@marsgeneral.com"
$Subject = "Email subject goes here"
$Body = "Email body goes here"

# The password is an app-specific password if you have 2-factor-auth enabled
$Password = "<>" | ConvertTo-SecureString -AsPlainText -Force
$Credential = New-Object -TypeName System.Management.Automation.PSCredential -ArgumentList $From, $Password
Send-MailMessage -From $From -To $To -Subject $Subject -Body $Body -SmtpServer "smtp.gmail.com" -port 587 -UseSsl -Credential $Credential
