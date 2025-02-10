Data exchange between [ScanMyTesla](https://www.scanmytesla.com/) and [TeslaLogger](https://github.com/bassmaster187/TeslaLogger), with example PHP scripts that log the data.

The ScanMyTesla code that sends data was written by the author of TeslaLogger. The author of ScanMyTesla doesn’t want to modify that code, not even to add a text box to change the server where the data is sent.

When in ScanMyTesla is TeslaLogger token set:
* POST request to https://teslalogger.de/check_token.php is send:
```logfile
    Time: 2025-02-09 16:29:40
    IP: 192.168.10.113
    Method: POST
    URL: /check_token.php
    Headers:
    Connection: Keep-Alive
    Host: teslalogger.de
    User-Agent: Dalvik/2.1.0 (Linux; U; Android 7.0; SM-A310F Build/NRD90M)
    Content-Length: 12
    Accept-Encoding: identity
    Content-Type: application/x-www-form-urlencoded

    Body:
    t=TOKEN
```

    (curl -X POST "https://teslalogger.de/check_token.php" -H "Connection: Keep-Alive" -H "Host: teslalogger.de" -H "User-Agent: Dalvik/2.1.0 (Linux; U; Android 7.0; SM-A310F Build/NRD90M)" -H "Accept-Encoding: identity" -H "Content-Type: application/x-www-form-urlencoded" --data "t=TOKEN")

    token 00000000 can be used for testing, from TeslaLogger source it seem that this token is used for testing

* Response can be:
  * "not found"
  * "Resource Limit Is Reached" (maybe?)
  * "ERROR: ..." (maybe?)
  * "OK"

* If OK is recived then ScanMyTesla start sending data to https://teslalogger.de/insert_scanmytesla.php as POST request:
  * Body tokens: t="token" v="version?" m="json data in Percent-encoding format"

```logfile
------------------------------
Time: 2025-02-09 18:33:05
IP: 192.168.10.113
Method: POST
URL: /insert_scanmytesla.php
Headers:
Connection: Keep-Alive
Host: teslalogger.de
User-Agent: Dalvik/2.1.0 (Linux; U; Android 7.0; SM-A310F Build/NRD90M)
Content-Length: 211
Accept-Encoding: identity
Content-Type: application/x-www-form-urlencoded

Body:
t=ggdfghjizffc&v=1&m=%7B%22d%22%3A%222025-02-09T19%3A33%3A19.697553%2B01%3A00%22%2C%22dict%22%3A%7B%22415%22%3A458.5%2C%22442%22%3A-1.759999999999998%2C%22426%22%3A-673.75681818182375%2C%2243%22%3A1.1858120000000085%2C%22404%22%3A6.4%2C%22445%22%3A6.3000000000000007%2C%22444%22%3A6.3000000000000007%7D%7D
------------------------------
```

So m=

```json
{
  "d": "2025-02-09T19:33:19.697553+01:00",
  "dict": {
    "415": 458.5,
    "442": -1.759999999999998,
    "426": -673.75681818182375,
    "43": 1.1858120000000085,
    "404": 6.4,
    "445": 6.3000000000000007,
    "444": 6.3000000000000007
  }
}
```

JSON keys meaning from the TeslaLogger source:
```
key d: datetime
key dict.2: SMTCellTempAvg
key dict.5: SMTCellMinV
key dict.6: SMTCellAvgV
key dict.7: SMTCellMaxV
key dict.9: SMTACChargeTotal
key dict.11: SMTDCChargeTotal
key dict.27: SMTCellImbalance
key dict.28: SMTBMSmaxCharge
key dict.29: SMTBMSmaxDischarge
key dict.43: SMTBatteryPower
key dict.71: SMTNominalFullPack
key dict.442: SMTSpeed (if value is 287.6 then Sihnel is not avalible)
ScanMyTesla sends a lot more data, but TeslaLogger (as of the code state on 2025-02-09) saves only the keys listed above.
```

* The TeslaLogger server (teslalogger.de) stores the data, and according to the ScanMyTesla webpage, "Data will be deleted immediately after being downloaded to your Teslalogger".
  * A TeslaLogger instance sends a POST request with a token to http://teslalogger.de/get_scanmytesla.php, where it probably receives the data in the same format as ScanMyTesla sends.