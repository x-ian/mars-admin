from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from selenium.webdriver.common.keys import Keys


from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

import time

from PIL import Image

import random

options = webdriver.ChromeOptions()
options.add_argument("headless=new")
options.add_argument("user-data-dir=/home/pi/.config/chromium")
options.add_argument("profile-directory=Profile 1")

############ all attempts to avoid bot detection
# adding argument to disable the AutomationControlled flag 
options.add_argument("--disable-blink-features=AutomationControlled") 
# exclude the collection of enable-automation switches 
options.add_experimental_option("excludeSwitches", ["enable-automation"]) 
# turn-off userAutomationExtension 
options.add_experimental_option("useAutomationExtension", False) 

driver = webdriver.Chrome(options=options)

############ all attempts to avoid bot detection
driver.execute_cdp_cmd("Page.addScriptToEvaluateOnNewDocument", {
    "source":
        "const newProto = navigator.__proto__;"
        "delete newProto.webdriver;"
        "navigator.__proto__ = newProto;"
    })
# changing the property of the navigator value for webdriver to undefined 
driver.execute_script("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})") 
useragentarray = [
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36",
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
]
driver.execute_cdp_cmd(
    "Network.setUserAgentOverride", {"userAgent": random.choice(useragentarray)}
)

#driver.add_cookie({'name': 'consentUUID', 'value': '09213633-5267-4d2e-b574-421699314d98_18', 'domain': '.kachelmannwetter.com'})

#driver.get('https://kachelmannwetter.com/de/vorhersage/2862485-nierstein/kompakt1x1')
#driver.get('https://kachelmannwetter.com/')
#driver.add_cookie({'name': 'consentUUID', 'value': '09213633-5267-4d2e-b574-421699314d98_18', 'domain': '.kachelmannwetter.com'})
driver.get('https://kachelmannwetter.com/de/vorhersage/2862485-nierstein/kompakt1x1')

#time.sleep(10)
#driver.implicitly_wait(10)
#with open("debug.html", "w", encoding="utf-8") as f:
#    f.write(driver.page_source)

wait = WebDriverWait(driver, 20)
e = wait.until(EC.presence_of_element_located((By.ID, "weather-forecast-compact")))

e = driver.find_element(By.ID, "weather-forecast-compact")
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

driver.execute_script ("document.getElementById('weather-forecast-compact').scrollIntoView();")
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

