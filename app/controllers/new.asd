"mform_isexpanded_id_availability"=>"1",
"assignsubmission_comments_enabled"=>"1",
"assignfeedback_editpdf_enabled"=>"1",
"mform_isexpanded_id_submissiontypes"=>"1",


"course"=>$this->course,
"coursemodule"=>"",
"section"=>"2", 					//ubicación
"module"=>"31",
"modulename"=>"assign",
"instance"=>"",
"add"=>"assign",
"update"=>"0",
"return"=>"0",
"sr"=>"0",
"sesskey"=>$this->sesskey, 			//sesskey
"_qf__mod_assign_mod_form"=>"1",
"mform_isexpanded_id_general"=>"1",
"mform_isexpanded_id_feedbacktypes"=>"1",
"mform_isexpanded_id_submissionsettings"=>"1",
"mform_isexpanded_id_groupsubmissionsettings"=>"1",
"mform_isexpanded_id_notifications"=>"1",
"mform_isexpanded_id_modstandardgrade"=>"1",
"mform_isexpanded_id_modstandardelshdr"=>"1",
"mform_isexpanded_id_availabilityconditionsheader"=>"1",
"name"=>$title,											//titulo
"introeditor[text]"=>"<p>Entregar antes de ".$date->format('m/d/Y')." a las 23:55</p>",				//descipcion en html
"introeditor[format]"=>"1",
"introeditor[itemid]"=>"966059896",

"allowsubmissionsfromdate[day]"=>$startday, 					//fecha inicio
"allowsubmissionsfromdate[month]"=>$startmonth, 					//fecha inicio
"allowsubmissionsfromdate[year]"=>$startyear,  				//fecha inicio
"allowsubmissionsfromdate[hour]"=>"0",  					//fecha inicio
"allowsubmissionsfromdate[minute]"=>"0",  				//fecha inicio
"allowsubmissionsfromdate[enabled]"=>"1", 				//fecha inicio
"duedate[day]"=>$endday, 									//fecha fin
"duedate[month]"=>$endmonth,  									//fecha fin
"duedate[year]"=>$endyear,  								//fecha fin
"duedate[hour]"=>"23", 									//fecha fin
"duedate[minute]"=>"55", 									//fecha fin
"duedate[enabled]"=>"1", 									//fecha fin
"alwaysshowdescription"=>"1",
"assignsubmission_onlinetext_enabled"=>"1",
"assignsubmission_file_enabled"=>"1",
"assignsubmission_file_maxfiles"=>"1",
"assignsubmission_file_maxsizebytes"=>"0",
"submissiondrafts"=>"0",
"requiresubmissionstatement"=>"0",
"attemptreopenmethod"=>"none",
"teamsubmission"=>"1",
"teamsubmissiongroupingid"=>"0",
"sendnotifications"=>"1", //notificacion a ayudante
"grade"=>"0",
"advancedgradingmethod_submissions"=>"",
"gradecat"=>"20828",
"blindmarking"=>"0",
"markingworkflow"=>"0",
"visible"=>"1",
"cmidnumber"=>"",
"groupmode"=>"1",
"availabilityconditionsjson"=>'{"op":"&","c":[{"type":"date","d":">=","t":1425697200}],"showc":[false]}',
"submitbutton"=>"Guardar cambios y mostrar",