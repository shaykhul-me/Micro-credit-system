import requests
import time

URL = "https://nextbarisal.com/"

while True:
    response = requests.get(URL)
    if response.status_code == 200:
        print("Website is reachable.")
    else:
        print("Website is not reachable.")
    time.sleep(2)
    print("Finished checking the website.")
    break

