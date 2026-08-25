# KeystrokeLoggingDashboard
A Python-based background keystroke logging application and a PHP dashboard for analysis.

---

## Features
* **Background Monitoring:** Utilizes Python (`pynput`) to quietly capture input data and system telemetry.
* **HTTP POST Transmission:** Transmits logs and host information to a central server.
* **PHP Dashboard Interface (`view.php`):**
  *Real-time auto refreshing log viewer (5-second interval).
  *System telemetry display tracking both client hostname & IP configurations.
  *Security management features including remote log clearing and safe `.txt` export functionality.
## Tech Stack
* **Client:** Python (`pynput`, HTTP requests)
* **Server / Dashboard:** PHP, HTML/CSS, JavaScript
> **Disclaimer:** This project was developed for academic and cybersecurity educational purposes only. Unauthorized use of keystroke loggers or monitoring software against target systems without explicit prior consent is illegal and violates ethical standards. The author assumes no liability for any misuse or damage caused by this software.

##Project Structure
```text
word.exe.py    #Python background client logger
receive.php    #backend ingestion script for POST data
view.php       # Monitoring and management dashboard
  
