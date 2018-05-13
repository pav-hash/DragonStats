
import socket
import json


class CgminerAPI(object):
    """ Cgminer RPC API wrapper. """
    def __init__(self, host='192.168.100.233', port=4028):
        self.data = {}
        self.host = host
        self.port = port

    def command(self, command, arg=None):
        """ Initialize a socket connection,
        send a command (a json encoded dict) and
        receive the response (and decode it).
        """
        sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)

        try:
            sock.connect((self.host, self.port))
            payload = {"command": command}
            if arg is not None:
                # Parameter must be converted to basestring (no int)
                payload.update({'parameter': unicode(arg)})

            sock.send(json.dumps(payload))
            received = self._receive(sock)
        finally:
            sock.shutdown(socket.SHUT_RDWR)
            sock.close()
        
#        return json.loads(received[:-1])
        # the null byte makes json decoding unhappy
        # also add a comma on the output of the `stats` command by replacing '}{' with '},{'
        return json.loads(received[:-1].replace('}{', '},{'))

    def _receive(self, sock, size=4096):
        msg = ''
        while 1:
            chunk = sock.recv(size)
            if chunk:
                msg += chunk
            else:
                break
        return msg

    def __getattr__(self, attr):
        def out(arg=None):
            return self.command(attr, arg)
        return out


