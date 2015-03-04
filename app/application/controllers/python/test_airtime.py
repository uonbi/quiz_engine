import sys

from AfricasTalkingGateway import AfricasTalkingGateway, AfricasTalkingGatewayException

username   = "MyUsername";
apikey     = "MyAPIKey";

recipients = [{"phoneNumber" : "+2547XXYYYZZZ", 
               "amount"      : "KES 10"}]

gateway    = AfricasTalkingGateway(username, apikey)

try:
    responses = gateway.sendAirtime(recipients)
    for response in responses:
        print "phoneNumber=%s; amount=%s; status=%s; discount=%s; requestId=%s" % (response['phoneNumber'],
                                                                   response['amount'],
                                                                   response['status'],
																   response['discount']
                                                                   response['requestId'])

except AfricasTalkingGatewayException, e:
    print 'Encountered an error while making the call: %s' % str(e)
