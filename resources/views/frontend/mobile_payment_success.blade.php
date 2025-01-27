<!DOCTYPE html>
<html>
<head>
<style>
body {
	display: flex;
	height: 100vh;
	flex-direction: column;
	justify-content: center;
}

.button {
    /* background-color: #4CAF50 */;
  background-color: #674FA5;
   /* box-shadow: 0px 3px 5px -1px rgba(21, 0, 0, 0.59); */;
  box-shadow: 0px 3px 8px -2px rgba(103, 79, 165, 0.93);
  border-radius: 15px;
  border: none;
  color: white;
  padding: 20px 42px;
  text-align: center;
  text-decoration: none;
  display: inline-block;
  font-size: 35px;
  margin: 4px 2px;
  cursor: pointer;
}
</style>

<script type="text/javascript">
    function invokeNative() {
        MessageInvoker.postMessage("{\"invoice_id\":\"{{ $invoice->id }}\"}");
    }
</script>

</head>
    <body>
        <div style="width:100%;text-align:center;">
            <img height="150" src="{{ asset('logo.png')}}">
            <h1 style="color:green; font-size: 45px;">Your payment successfully completed!</h1><br>
            {{-- <br>
            <input type="button" class="button" value="View Details" onclick="invokeNative()"> --}}
        </div>
        <script>

        </script>
    </body>
</html>
