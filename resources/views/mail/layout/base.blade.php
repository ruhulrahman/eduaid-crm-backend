<!DOCTYPE HTML
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
  xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
  <!--[if gte mso 9]>
<xml>
  <o:OfficeDocumentSettings>
    <o:AllowPNG/>
    <o:PixelsPerInch>96</o:PixelsPerInch>
  </o:OfficeDocumentSettings>
</xml>
<![endif]-->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="x-apple-disable-message-reformatting">
  <!--[if !mso]><!-->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!--<![endif]-->
  <title></title>

  <style type="text/css">
    @media only screen and (min-width: 620px) {
      .u-row {
        width: 600px !important;
      }

      .u-row .u-col {
        vertical-align: top;
      }

      .u-row .u-col-100 {
        width: 600px !important;
      }

    }

    @media (max-width: 620px) {
      .u-row-container {
        max-width: 100% !important;
        padding-left: 0px !important;
        padding-right: 0px !important;
      }

      .u-row .u-col {
        min-width: 320px !important;
        max-width: 100% !important;
        display: block !important;
      }

      .u-row {
        width: calc(100% - 40px) !important;
      }

      .u-col {
        width: 100% !important;
      }

      .u-col>div {
        margin: 0 auto;
      }
    }

    body {
      margin: 0;
      padding: 0;
    }

    table,
    tr,
    td {
      vertical-align: top;
      border-collapse: collapse;
    }

    p {
      margin: 0;
    }

    .ie-container table,
    .mso-container table {
      table-layout: fixed;
    }

    * {
      line-height: inherit;
    }

    a[x-apple-data-detectors='true'] {
      color: inherit !important;
      text-decoration: none !important;
    }

    table,
    td {
      color: #000000;
    }

    #u_body a {
      color: #0000ee;
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      #u_content_image_1 .v-src-width {
        width: auto !important;
      }

      #u_content_image_1 .v-src-max-width {
        max-width: 96% !important;
      }

      #u_content_heading_1 .v-font-size {
        font-size: 26px !important;
      }

      #u_content_text_4 .v-container-padding-padding {
        padding: 0px 10px 40px !important;
      }

      #u_content_text_4 .v-line-height {
        line-height: 170% !important;
      }

      #u_content_button_1 .v-size-width {
        width: 60% !important;
      }

      #u_content_text_3 .v-container-padding-padding {
        padding: 0px 20px 20px !important;
      }

      #u_content_text_2 .v-container-padding-padding {
        padding: 0px 10px 80px !important;
      }

      #u_content_image_2 .v-src-width {
        width: auto !important;
      }

      #u_content_image_2 .v-src-max-width {
        max-width: 32% !important;
      }
    }
    .bg-primary {
      /* background-color: #6c6cd0; */
      background-color: #5a8dee;
      color: #FFF !important;
    }
    .bg-success {
      background-color: #39da8a;
      color: #FFF !important;
    }
    .bg-danger {
      background-color: #ff5b5c;
      color: #FFF !important;
    }
    .bg-secondary {
      background-color: #69809a;
      color: #FFF !important;
    }
    .bg-warning {
      background-color: #fdac41;
      color: #FFF !important;
    }
    .bg-info {
      background-color: #00cfdd;
      color: #FFF !important;
    }
    .bg-dark {
      background-color: #495563;
      color: #FFF !important;
    }
    body{
      font-family: Ubuntu,Helvetica,Arial,sans-serif;
      font-size: 13px;
      line-height: 1.3;
      text-align: justify;
      color: #555555;
    }
  </style>



  <!--[if !mso]><!-->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&display=swap" rel="stylesheet" type="text/css">
  <!--<![endif]-->

</head>

<body class="clean-body u_body"
  style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #ffffff;color: #000000">

  <table id="u_body"
    style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;mso-table-lspace: 0pt;mso-table-rspace: 0pt;vertical-align: top;min-width: 320px;Margin: 0 auto;background-color: #ffffff;width:100%"
    cellpadding="0" cellspacing="0">
    <tbody>
      <tr style="vertical-align: top">
        <td style="word-break: break-word;border-collapse: collapse !important;vertical-align: top">

          {{-- Content Section --}}
            @yield('content')
            @include('mail.layout.footer')
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>
