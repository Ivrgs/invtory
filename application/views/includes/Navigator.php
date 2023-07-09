<body>
<div class="wrapper">
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark sticky-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?= base_url();?>">Ivrgs Inventory</a>
            </div>
            <ul class="nav navbar-nav">
            <div class="UpperOption">
                    <a href="<?= base_url()."Home/Logout"?>"><button class="btn btn-default"  title="Logout"><i class="fa fa-sign-out"></i></button></a>
                    <button class="btn btn-success MainButton" title="Add New Game" onclick="add_game()"  data-toggle="modal" data-target="#ModalAddGame"> <span class="icon mobile"><img src="assets/images/add_games.svg" /></span><p class="ButtonName"> Add New</p></button>
                    <button class="btn btn-info MainButton" title="Add New Content" onclick="cms_add()" data-toggle="modal" data-target="#ModalCMSAdd"><span class="icon mobile"><img src="assets/images/cms_add.svg" /></span><p class="ButtonName"> CMS-Add</p></button>
                    <button class="btn btn-info MainButton" title="Update Content" onclick="cms_edit()" data-toggle="modal" data-target="#ModalCMSUpdate"><span class="icon mobile"><img src="assets/images/cms_update.svg" /></span><p class="ButtonName"> CMS-Update</p></button>
					<button class="btn btn-primary MainButton" title="Register User" onclick="RegisterUser()" data-toggle="modal" data-target="#ModalRegister"><span class="icon mobile"><img src="assets/images/export_games.svg" /></span><p class="ButtonName">Register</p></button></a>
					<button class="btn btn-warning MainButton" title="Export Data" data-toggle="modal" data-target="#ModalExport"><span class="icon mobile"><img src="assets/images/export_cms.svg" /></span><p class="ButtonName">Export Data</p></button></a>
                </div>
            </ul>
        </div>
    </nav>
    <div class="aboveBackground"></div>