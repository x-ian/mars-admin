from selenium import webdriver
#from selenium.webdriver.chrome.options import Options
#from selenium.webdriver.common.by import By
#from selenium.webdriver.common.keys import Keys
#import time

#from PIL import Image

#options = webdriver.ChromeOptions()
#options.add_argument("headless=new")
#options.add_argument("user-data-dir=/home/pi/.config/chromium")
#options.add_argument('profile-directory=Default')

driver = webdriver.Chrome()

#driver.add_cookie({'name': 'consentUUID', 'value': '09213633-5267-4d2e-b574-421699314d98_18', 'domain': '.kachelmannwetter.com'})

#driver.get('https://kachelmannwetter.com/de/vorhersage/2862485-nierstein/kompakt1x1')
#driver.get('https://kachelmannwetter.com/')
#driver.add_cookie({'name': 'consentUUID', 'value': '09213633-5267-4d2e-b574-421699314d98_18', 'domain': '.kachelmannwetter.com'})
driver.get('http://192.168.1.8/#hId-pgUsageReport')

time.sleep(10)
driver.implicitly_wait(10)

e = driver.find_element(By.ID, "appUsageReport-ti")

html = driver.execute_script("return document.documentElement.outerHTML;")

time.sleep(2)
print(html)

driver.quit()

