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

  The token 00000000 can be used for testing. From the TeslaLogger source, it seems that this token is intended for testing purposes.

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
key dict.442: SMTSpeed (if value is 287.6 then speed is not avalible)
```
ScanMyTesla sends a lot more data, but TeslaLogger (as of the code state on 2025-02-09) saves only the keys listed above.
What "IDs" I seen:
```csv
1,2,3,4,5,6,7,9,11,13,16,20,23,24,25,27,28,29,43,59,61,64,80,87,101,102,103,104,105,106,107,108,109,110,111,112,113,114,115,116,117,118,119,120,121,122,123,124,125,126,127,128,129,130,131,132,133,134,135,136,137,138,139,140,141,142,143,144,145,146,147,148,149,150,151,152,153,154,155,156,157,158,159,160,161,162,163,164,165,166,167,168,169,170,171,172,173,174,175,176,177,178,179,180,181,182,183,184,185,186,187,188,189,190,191,192,193,194,195,196,404,415,426,442,444,445
```

* The TeslaLogger server (teslalogger.de) stores the data, and according to the ScanMyTesla webpage, "Data will be deleted immediately after being downloaded to your Teslalogger".
  * A TeslaLogger instance sends a POST request with a token to http://teslalogger.de/get_scanmytesla.php, where it probably receives the data in the same format as ScanMyTesla sends.