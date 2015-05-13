<?php

define( 'LOCK_FILE', __DIR__."/Consumer.php".".lock" ); 

class ProductLiveService {

	/*
	*	Start the service
	*
	*/
	function startService() {
		if($this->isRunning()==false) {
			//echo 'start running<br>';
			$this->execInBackground("php ".__DIR__."/Consumer.php "._PS_BASE_URL_.__PS_BASE_URI__);
			//echo 'started exec in background<br>';
			return true;
		} else {
			return false;
		}
	}

	/*
	*	Force the service to stop
	*
	*/
	function forceServiceToStop() {
		if( file_exists( LOCK_FILE ) ) {
			$lockingPID = trim( file_get_contents( LOCK_FILE ) ); 
			if (substr(php_uname(), 0, 7) == "Windows"){ 
		    	$this->win_kill($lockingPID);
		    } 
		    else { 
		        //posix_kill($lockingPID, 0);
		        exec("kill -9 ".$lockingPID);
				unlink( LOCK_FILE );
		    } 
		}
		
	}

	/*
	*	Check if the process is already running
	*
	*/
	function isRunning() 
	{ 

	    if( file_exists( LOCK_FILE ) ) 
	    { 
	        # check if it's stale 
	        $lockingPID = trim( file_get_contents( LOCK_FILE ) ); 
	        //echo "PID: ".$lockingPID."<br>";

	        # Get all active PIDs. 
	        $pids = explode( "\n", trim( `ps -e | awk '{print $1}'` ) ); 
	        
	        //echo "PIDS: <br>";
	        //echo print_r($pids);

	        # If PID is still active, return true 
	        if( in_array( $lockingPID, $pids ) ) {
	        	//echo "heeeeeeeeeere";
	        	return true; 
	        } 
	        // for windows
	        $tasks = shell_exec( "tasklist.exe" );
	        if (strpos($tasks, strval($lockingPID))!=false) {
	        	//echo "found";
	        	return true;
	        } else {
	        	unlink( LOCK_FILE ); 
	        	return false;
	        }
	    } else {
	    	//echo "the file does not exist<br>";
	    	return false;
	    }

	} 

	function execInBackground($cmd) {
	    if (strpos(php_uname(),'Windows') !== false){ 
	    	//echo 'windows<br>';
	    	//echo $cmd.'<br>';
	    	$WshShell = new COM("WScript.Shell");
			$oExec = $WshShell->Run($cmd, 3, true);
			//var_dump($oExec);
	    } 
	    else { 
	        exec($cmd . " > /dev/null &");   
	    } 
	}

	function win_kill($pid){ 
	    $wmi=new COM("winmgmts:{impersonationLevel=impersonate}!\\\\.\\root\\cimv2"); 
	    $procs=$wmi->ExecQuery("SELECT * FROM Win32_Process WHERE ProcessId='".$pid."'"); 
	    foreach($procs as $proc) 
	      $proc->Terminate(); 
	}
}

