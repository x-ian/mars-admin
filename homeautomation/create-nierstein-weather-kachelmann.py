from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys
import time

from PIL import Image

options = webdriver.ChromeOptions()
options.add_argument("headless=new")
options.add_argument("user-data-dir=/home/pi/.config/chromium")
options.add_argument('profile-directory=Default')

driver = webdriver.Chrome(options=options)

#driver.add_cookie({'name': 'consentUUID', 'value': '09213633-5267-4d2e-b574-421699314d98_18', 'domain': '.kachelmannwetter.com'})

#driver.get('https://kachelmannwetter.com/de/vorhersage/2862485-nierstein/kompakt1x1')
#driver.get('https://kachelmannwetter.com/')
#driver.add_cookie({'name': 'consentUUID', 'value': '09213633-5267-4d2e-b574-421699314d98_18', 'domain': '.kachelmannwetter.com'})
driver.get('https://kachelmannwetter.com/de/wetter/2862485-nierstein')

time.sleep(10)
driver.implicitly_wait(10)

e = driver.find_element(By.ID, "weather-overview-compact")
size = e.size
w, h = size['width'], size['height']
#driver.execute_script ("window.scrollTo(0,h);")

#driver.switch_to.default_content()
#time.sleep(10)
#driver.switch_to.frame(25)
#driver.find_element(By.ID, "btn-reject").click()

#driver.implicitly_wait(30)
#driver.find_element(By.CSS_SELECTOR, 'button.message-component').click()
#driver.implicitly_wait(30)
#driver.find_element(By.TAG_NAME, 'body').send_keys(Keys.ENTER)

driver.execute_script ("document.getElementById('weather-overview-compact').scrollIntoView();")
driver.execute_script("window.scrollBy(0,-120)");
#driver.execute_script ("document.getElementById('forecast-url').scrollIntoView();")

driver.save_screenshot('screenie.png')

img = Image.open('./screenie.png')
box = (30, 125, 770, 387)
img2 = img.crop(box)
img2.save('screenie_cropped.png')

#html = driver.page_source

html = driver.execute_script("return document.documentElement.outerHTML;")

time.sleep(2)
#print(html)

driver.quit()

