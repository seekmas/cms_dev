
var ppInstaller = new Object();

ppInstaller.submitform = function() 
{
	 if(1 != this.needToContinue || this.flag) {
		 var msg = 'Please wait, Current action is running.' ; 
		 if('display' == this.task ){ 
			 msg = 'You dont have permit to next action.';
		 }
		 alert(msg);
		 return false;
	 }
 	this.form.submit();
};
		
ppInstaller.needToSubmit= function()
{
	if(-1 >= this.interval){
		return false;
	}
	this.interval = -1;
	this.form.submit();
};

ppInstaller.warning = function()
{
	alert('Something is going wrong, Please contact to PayPlans Support Team.');
};


ppInstaller.autoRequest = function()
{
    ppInstaller.hideButton();
    window.setInterval(function(){ ppInstaller.needToSubmit(); },500);
    //if time limit goes more than 30 sec then alert will be appeared
    window.setInterval( function(){ppInstaller.warning()},300000);
};

ppInstaller.hideButton = function(){
    document.getElementById("ppInstaller_submit_button").style.display = 'none'; 
    document.getElementById("ppInstaller_spinner").style.display = 'block'; 
};

onload = function()
	{
		 ppInstaller.form 		= document.adminForm;
		 ppInstaller.task 		= ppInstaller.form.task.value;
		 ppInstaller.needToContinue 	= Number(ppInstaller.form.needToContinue.value);
		 ppInstaller.flag 		= false; 
		 ppInstaller.interval	= 1;
		 
		 //Migration Process
		 if(ppInstaller.task == 'migrate' && 0 == ppInstaller.needToContinue){
			 ppInstaller.flag = true;
		 } 
		 /**
		  * Installation Process
		  */
		 // If sub task is undefine then we are on decision making stage 
		 if(
		     ('install' == ppInstaller.task  && 'undefined' != typeof(ppInstaller.form.subtask))
			 || ('patch'   == ppInstaller.task) 
			 || ('complete' == ppInstaller.task )
		 	)
		 	{	 ppInstaller.flag = true;	 }
		 		 
		 if(ppInstaller.flag){
			 ppInstaller.autoRequest();
		 }
	};

ppInstaller.confirmRevert = function(){
    var r=confirm("Are you sure to revert?");
    if (r==true)
      {
        window.location = 'index.php?option=com_ppinstaller&task=revert&remove=true';
      }
    else
      {
        return false;
    }
};