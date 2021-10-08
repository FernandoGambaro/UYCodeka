<?php

ini_set('display_errors', 1); // see an error when they pop up
error_reporting(E_ALL); // report all php errors
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../library/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="../library/bootstrap/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="../library/js/jquery-ui.min.css" />
    <!-- link rel="stylesheet" href="../pacientes/assets/css/style.css" / -->

<link href="../library/bootstrap/bootstrap.css" rel="stylesheet"/>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

    <script src="../library/js/OpenWindow-rejilla.js" type="text/javascript"></script>

    <link rel="stylesheet" href="../library/toastmessage/jquery.toastmessage.css" type="text/css">
    <script src="../library/toastmessage/jquery.toastmessage.js" type="text/javascript"></script>
    <script src="../library/toastmessage/message.js" type="text/javascript"></script>
            
    <script type="text/javascript" src="../library/js/jquery.keyz.js"></script>
    <script src="../library/js/moment.js"></script>
        <!-- some custom CSS -->

    <style>
        body
        {
            font-size: 100%;
            margin: 0;
            padding: 0;
        }            
        .left-margin{
            margin:0 .5em 0 0;
        }

        .right-button-margin{
            margin: 0 0 1em 0;
            overflow: hidden;
        }
        .table-fuente10{
            font-size: 10px;
        } 
        @media (min-width: 768px) {
            .container{
                width: 100%;
            }             
            label.col-xs-12 {
            text-align: left !important;
            }
        }  
        fieldset 
        {
            border: 1px solid #ddd !important;
            margin: 0;
            min-width: 0;
            padding: 10px;       
            position: relative;
            border-radius:4px;
            background-color:#f5f5f5;
            padding-left:10px!important;
        }		
		legend
		{
			font-size:14px;
			font-weight:bold;
			margin-bottom: 0px; 
			width: 35%; 
			border: 1px solid #ddd;
			border-radius: 4px; 
			padding: 5px 5px 5px 10px; 
			background-color: #ffffff;
		} 
        .table td.fit, 
        .table th.fit {
            white-space: nowrap;
            width: 1%;
        }   
        .table tbody tr:nth-child(odd) td {
        
        }

        .table tbody tr.highlight td { 
            
        }  
        .pagination {
            display: inline-block;
            padding-left: 0;
            margin: 0 0;
            border-radius: 4px;
        }                            
    </style>


    </head>
    <body >
        <!-- container -->
        <div>
         <!-- For the following code look at footer.php -->