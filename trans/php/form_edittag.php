<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDC PE Translator</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>

<link rel="stylesheet" href="../udcedit.css" type="text/css" />
<link rel="stylesheet" href="dtree.css" type="text/css" />
<link rel="shortcut icon" href="../images/udc.ico" type="image/x-icon" />

<script language="javascript" src="udcedit.js" type="text/javascript" ></script>
<script language="javascript" src="dtree.js" type="text/javascript" ></script>
<script language="javascript" src="php.default.js" type="text/javascript" ></script>
</head>


<body>
	<div class="transpane">
		<div class="leftpane">#displaytree#</div>
		<div class="rightpane">
			<form id="udcform" name="udcform" method="post" action="edittag.php" accept-charset="UTF-8">
				<div class="searchbox">
					<span style="float:right; overflow:none;">
						Target Language&nbsp;
						<select id="targetlanguage" name="targetlanguage" class="combobox">
						#target_language#
						</select>
					</span>
					<span style="float:right; overflow:none;">
						Source Language&nbsp;
						<select id="language" name="language" class="combobox" style="background-color: #fdffbb">
						#source_languages#
						</select>&nbsp;&nbsp;&nbsp;&nbsp;
					</span>
				</div>		
				<div class="searchbox">
					<div class="editleftcolumn widecolumn">
						<div class="editlabel fixedwidthlabel">SEARCH</div>
						<div class="editvalue">
							<input type="text" id="searchterm" name="searchterm" class="textinput" size="15" value="#searchterm#" />
						</div>
						<div style="float: left; line-height: 32px; vertical-align: middle;">
							&nbsp;
							<input class="inputbutton" type="submit" id="SearchButton" name="Search" value="Search" />
							<input class="inputbutton" type="submit" name="Edit" value="Edit" onclick="javascript:EditForm(); return false;" #showeditbutton#/>
							<!--input class="inputbutton" type="submit" name="Edit" value="Edit" /-->
							<!--input class="inputbutton" type="submit" name="New" value="New" onclick="javascript:NewRecord(); return false;"/-->
							<!--input class="inputbutton" type="submit" name="Clone" value="Clone" onclick="javascript:CloneRecord(); return false;" /-->
							<!--input type="submit" id="SubmitSave" name="SubmitSave" value="Save" style="display:none" /-->				
							<input type="submit" id="SubmitSave" name="SubmitSave" value="Save" style="display:none"/> 	
							<input type="submit" id="EditCancel" name="EditCancel" value="Cancel" style="display:none" onclick="javascript:CancelEditForm(); return false;"/>				
						</div>
					</div>
				</div>
				<div id="errorbox" class="errorbox" style="display:#errorshow#">
					#errorreasons#
				</div>
				<div id="successbox" class="successbox" style="display:#successshow#">
					Record saved successfully
				</div>
				<div class="searchbox" style="display: none">
					<div class="editleftcolumn">
						<div id="notationlabel" class="editlabel fixedwidthlabel">Notation</div>
						<div id="notation" class="editvalue fixedwidthvalue">#notation#</div>
						<div id="notationeditlabel" class="editvalue" style="display:none">
							<input id="notationedit" name="notationedit" class="textinput" type="text" size="40" value="#notation#" onBlur="SetNotation(); return true;" style="display:none" />
						</div>
					</div>
					<div class="editrightcolumn rightalign">
						<div class="editlabel fixedwidthlabel nonedit">ID&nbsp;</div>
						<div id="mfn" name="mfn" class="editvalue fixedwidthvalue">#mfn#<input type="hidden" id="mfnvalue" name="mfnvalue" value="#mfn#" /></div>
					</div>
					<div id="pagenumber" class="editrightcolumn rightalign" style="display: none">
						Page1&nbsp;<a href="#" onMouseDown="showPage('page2');">Page2</a>&nbsp;<a href="#" onMouseDown="showPage('page3');">Page3</a>
					</div>
					<div class="editrightcolumn rightalign" style="display: none">	
						<div class="editlabel">EUN&nbsp;</div>
						<div class="editvalue"><input id="EUN" name="EUN" class="textinput" type="text" size="30" value="#EUN#" /></div>						
					</div>
					<div class="editleftcolumn widecolumn" style="display: none">
						<div class="editlabel fixedwidthlabel ">Broader&nbsp;</div>
						<div class="editvalue">
							<input id="broader" name="broader" class="textinput #broadercategorycolor#" type="text" size="106" value="#broadercategory#" onBlur ="javascript:checkBroader(); return true;" />
						</div>				
		                <div class="editlabel fixedwidthlabel" style="display: none">
							Derived From&nbsp;
						</div>	
						<div class="editvalue">
							<input id="derivedfrom" name="derivedfrom" class="textinput #derivedfromcolor#" type="text" size="106" value="#derivedfrom#" onBlur ="javascript:checkDerivedFrom(); return true;" />
						</div>					
					</div>
				</div>
				<div id="page1" style="display:block">
					<div class="searchbox">
						<div class="editleftcolumn widecolumn">
							<div class="editlabel fixedwidthlabel">CAPTION</div>
							<div class="editvalue widevalue">
		                        <textarea class="edittextarea wideedit" id="caption" name="caption" rows="2" readonly="true" style="background-color: #fdffbb; margin-bottom: 5px;">#caption#</textarea><br/>
		                        <textarea class="edittextarea wideedit" id="transcaption" name="transcaption" cols="66" rows="2" readonly="true" style="background-color: #dfdfdf">#transcaption#</textarea>
		                    </div>
							<div class="errorbox" style="display:none;" id="captionerror">This is an error</div>
						</div>
						<div class="editleftcolumn widecolumn" #displayverbalexamples#>
							<div class="editlabel fixedwidthlabel">INCLUDING</div>
							<div class="editvalue widevalue">
		                        <textarea class="edittextarea wideedit" id="verbalexamples" name="verbalexamples" cols="66" rows="5" readonly="true" style="background-color: #fdffbb; margin-bottom: 5px;">#verbalexamples#</textarea><br/>
		                        <textarea class="edittextarea wideedit" id="transverbalexamples" name="transverbalexamples" readonly="true" cols="66" rows="5" style="background-color: #dfdfdf">#transverbalexamples#</textarea>
		                    </div>
						</div>
						<div class="editleftcolumn widecolumn" style="display: none">
							<div class="editlabel fixedwidthlabel">Info Note</div>
							<div class="editvalue widevalue"><textarea class="edittextarea #informationnotecolor#" id="informationnote" name="informationnote" cols="66" rows="5">#infonote#</textarea></div>
						</div>
						<div class="editleftcolumn widecolumn" #displayscopenote#>
							<div class="editlabel fixedwidthlabel">SCOPE NOTE</div>
							<div class="editvalue widevalue">
		                        <textarea class="edittextarea wideedit" id="scopenote" name="scopenote" cols="66" rows="5" readonly="true" style="background-color: #fdffbb; margin-bottom: 5px;">#scopenote#</textarea><br/>
		                        <textarea class="edittextarea wideedit" id="transscopenote" name="transscopenote" readonly="true" cols="66" rows="5" style="background-color: #dfdfdf">#transscopenote#</textarea>
		                    </div>
						</div>
						<div class="editleftcolumn widecolumn"  #displayappnote#>
							<div class="editlabel fixedwidthlabel">APP NOTE</div>
							<div class="editvalue widevalue">
		                        <textarea class="edittextarea wideedit" id="appnote" name="appnote" cols="66" rows="5" readonly="true" style="background-color: #fdffbb; margin-bottom: 5px;">#appnote#</textarea><br/>
		                        <textarea class="edittextarea wideedit" id="transappnote" name="transappnote" readonly="true" cols="66" rows="5" style="background-color: #dfdfdf" lang="nl">#transappnote#</textarea>
		                    </div>
						</div>
						<div class="editleftcolumn widecolumn" style="display: none">
							<div class="editlabel fixedwidthlabel">Refs</div>
							<div class="editvalue widevalue" id="references">#references#</div>
						</div>
						<div class="editleftcolumn widecolumn" #displayexamples#>
							<div class="editlabel fixedwidthlabel">EXAMPLES</div>
							<div class="editvalue widevalue" id="examples">#examples#</div>
						</div>
						<div class="editleftcolumn widecolumn" style="display: none;">
							<div class="editlabel fixedwidthlabel">Parallel Div Instructions</div>
							<div class="editvalue widevalue" id="pardivinst">#pardivinst#</div>
						</div>
						<div class="editleftcolumn widecolumn">
							<div class="editlabel fixedwidthlabel" style="line-height: 1.25em">Comments to Editor</div>
							<div class="editvalue widevalue">
		                        #comments#
		                        <textarea class="edittextarea wideedit" id="mycomments" name="mycomments" readonly="true" cols="66" rows="5" style="background-color: #dfdfdf" lang="nl">#mycomments#</textarea>
		                    </div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</body>
</html>
