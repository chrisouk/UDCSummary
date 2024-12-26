<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>UDCS Editor</title>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>

<link rel="stylesheet" href="../udcedit.css" type="text/css" />
<link rel="shortcut icon" href="../img/udc.ico" type="image/x-icon" />

<script language="javascript" src="udcedit.js" type="text/javascript" ></script>
<script language="javascript" src="php.default.js" type="text/javascript" ></script>
</head>


<body>
    <div id="pagecontainer">
        <div id="titleimagecontainer_thin">
            <span style="float:right; overflow:none; margin-top: 20px; vertical-align: middle;">
        	   Language&nbsp;<select id="language" name="language" class="combobox"><option value="1" #lang-eng#>English [eng]</option></select>
        	</span>
        </div>
        <div id="topmenu">#backtosearchresults##menuitems#</div>
    	<form id="udcform" name="udcform" method="post" action="edittag.php" accept-charset="UTF-8">
            <input type="hidden" name="scrollvalue" id="scrollvalue" value="#scrollvalue#" />
            <div id="validationdiv" name="validationdiv" style="display:#validationon#;">
            #validation#
            </div>
            <div id="searchcontainer" style="display:#validationoff#;">
        		<div class="searchbox searchkeywordbox">
					<div class="editleftcolumn">
						<div class="editlabel fixedwidthlabel rightmargin5">Notation Search</div>
						<div class="editvalue">
							<input type="text" id="notationsearchterm" name="notationsearchterm" class="textinput" style="width: 130px;" value="#notationsearchterm#" />
						</div>
						<div style="float: left; line-height: 32px; vertical-align: middle;">
							&nbsp;
							<input class="inputbutton" type="submit" id="SubmitNotationSearch" name="SubmitNotationSearch" value="Search" />
						</div>
					</div>
					<div class="editleftcolumn">
						<div class="editlabel fixedwidthlabel rightmargin5">Caption Search</div>
						<div class="editvalue">
							<input type="text" id="captionsearchterm" name="captionsearchterm" class="textinput" style="width: 130px;" value="#captionsearchterm#" />
						</div>
						<div style="float: left; line-height: 32px; vertical-align: middle;">
							&nbsp;
							<input class="inputbutton" type="submit" id="SubmitCaptionSearch" name="SubmitCaptionSearch" value="Search" />
						</div>
					</div>
        			<div class="editleftcolumn">
        				<div class="editvalue noborder advancedsearch">
        					<a href="advanced_search.php">Advanced Search</a>
                        </div>
        			</div>
        		</div>
        		<div class="searchbox searchkeywordbox" style="display:#validationoff#;">
                    <input type="hidden" name="scrollvalue" id="scrollvalue" value="#scrollvalue#" />
        			<div class="editleftcolumn">
                        <div id="searchresultsdiv" class="searchresults bottommargin5" onscroll="fScroll(this);">
                        #searchresults#
                        </div>
        			</div>
        		</div>
            </div>

            <div id="maincontainer" style="display:#validationoff#;">
        		<div class="searchbox">
        			<div class="editleftcolumn widecolumn">
        				<div class="editlabel fixedwidthlabel">UDC</div>
        				<div class="editvalue">
        					<input type="text" id="searchterm" name="searchterm" class="textinput inputfield" value="#searchterm#" />
        				</div>
                        <input type="hidden" name="notation" id="notation" value="#notation#" />
        				<div style="float: left; line-height: 32px; vertical-align: middle;">
        					&nbsp;
        					<input class="inputbutton" type="submit" id="SearchButton" name="Search" value="Search" />
        					<input type="submit" id="SubmitSave" name="SubmitSave" value="Save"/>
        					<input type="submit" id="EditCancel" name="EditCancel" value="Cancel" style="display:none" onclick="javascript:CancelEditForm(); return false;"/>
        					<input type="submit" id="PrevNotation" name="PrevNotation" value="Prev"/ style="#displayprevnotation#" onclick="javascript:GotoNotation('#prevnotation#'); return false;">
        					<input type="submit" id="NextNotation" name="NextNotation" value="Next"/ style="#displaynextnotation#" onclick="javascript:GotoNotation('#nextnotation#'); return false;">
        				</div>
            			<div class="editrightcolumn rightalign">
                        	<div class="editlabel nonedit rightmargin5">ID</div>
                            <div id="mfndiv" name="mfndiv" class="editvalue">#mfn#<input type="hidden" name="MFN" id="MFN" value="#mfn#" /><input type="hidden" name="mfnvalue" id="mfnvalue" value="#mfn#" /></div>
            			</div>
                    </div>
        		</div>
        		<div class="errorbox" style="display:#errorshow#">
        			#errorreasons#
        		</div>
        		<div class="errorbox successbox" style="display:#successshow#">
        			#successtype# saved successfully
        		</div>
        		<div class="searchbox">
        			<div id="pagenumber" class="editrightcolumn rightalign">
                    #pageaccess#
        			</div>
        			<div class="editleftcolumn">
                    	<div class="editlabel fixedwidthlabel nonedit"><span style="color: #9a9a9a;">001</span> Encoded </div>
                        <div id="EUN" class="editvalue fixedwidthvalue">#EUN#&nbsp;</div>
        			</div>
    				<div class="editleftcolumn widecolumn">
    					<div class="editlabel fixedwidthlabel"><span style="color: #9a9a9a;">100</span> Caption</div>
    					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue #captioncolor#" id="caption" name="caption" rows="1">#caption#</textarea></div>
    					<div class="errorbox" style="display:none;" id="captionerror">This is an error</div>
    				</div>
    				<div class="editleftcolumn widecolumn">
    					<div class="editlabel fixedwidthlabel"><span style="color: #9a9a9a;">105</span> Including</div>
    					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue #verbalexamplescolor#" id="verbalexamples" name="verbalexamples" rows="2">#verbalexamples#</textarea></div>
    				</div>
        		</div>
                <div id="page1" #page1access# >
        			<div class="searchbox">
        				<div class="editleftcolumn">
        					<div class="editlabel fixedwidthlabel "><span style="color: #9a9a9a;">002</span> Table</div>
        					<div class="editvalue">
        						<select name="headingtype" class="editcombo combowidth">
        							<option value="a" #heading-a#>Table Ia - Coordination. Extension</option>
        							<option value="b" #heading-b#>Table Ib - Relation. Subgrouping. Order-fixing</option>
        							<option value="c" #heading-c#>Table Ic - Common Auxiliaries of Language</option>
        							<option value="d" #heading-d#>Table Id - Common Auxiliaries of Form</option>
        							<option value="e" #heading-e#>Table Ie - Common Auxiliaries of Place</option>
        							<option value="f" #heading-f#>Table If - Common Auxiliaries of Ethnic Grouping</option>
        							<option value="g" #heading-g#>Table Ig - Common Auxiliaries of Time</option>
        							<option value="h" #heading-h#>Table Ih - Subject specication by notations from non-UDC sources</option>
        							<option value="i" #heading-i#>Table Ii - Common Auxiliaries of Viewpoint</option>
        							<option value="k" #heading-k#>Table Ik - Common Auxiliaries of Persons and Materials</option>
        							<option value="l" #heading-l#>Section II. Special auxiliary subdivisions</option>
        							<option value="M" #heading-M#>Main table</option>
        						</select>
        					</div>
        				</div>
        				<div class="editrightcolumn rightalign">
        					<div class="editlabel fixedwidthlabel ">Special Aux&nbsp;</div>
        					<div class="editvalue">
        						<select name="specialauxtype" class="editcombo combowidth">
        							<option value="0" #notspecialaux#>Not a special aux</option>
        							<option value="1" #hyphenaux#>hyphen (-) auxiliary</option>
        							<option value="2" #pointaux#>point nought (.0) auxiliary</option>
        							<option value="3" #apostropheaux#>apostrophe (') auxiliary</option>
        							<option value="4" #otheraux#>other, e.g. final digits (...1/...9)</option>
        						</select>
        					</div>
        				</div>
        				<div class="editleftcolumn">
        					<div class="editlabel fixedwidthlabel">Heading Type</div>
        					<div class="editvalue">
        						<select name="udcheadingtype" class="editcombo combowidth">
        							<option value="C" #CAux#>Single common auxiliary</option>
        							<option value="CS" #CSAux#>Single common auxiliary with Special Auxiliary</option>
        							<option value="CR" #CRAux#>Range of common auxiliary (/)</option>
        							<option value="CC" #CCAux#>Common auxiliary connected by :</option>
        							<option value="CP" #CPAux#>Common auxiliary connected by +</option>
        							<option value="M" #MAux#>Simple main number</option>
        							<option value="MS" #MSAux#>Combined special auxiliary</option>
        							<option value="MSS" #MSSAux#>Combined with special auxiliary</option>
        							<option value="outside" #outsideAux#>Special auxiliary table</option>
        							<option value="MC" #MCAux#>Combined with common auxiliary</option>
        							<option value="MCS" #MCSAux#>Combined with common and special auxiliary</option>
        							<option value="MM" #MMAux#>Two main numbers combined with / : or +</option>
        							<option value="MMSC" #MMSCAux#>Two main numbers combined with common or special auxiliary</option>
        						</select>
        					</div>
        				</div>
        				<div class="editrightcolumn rightalign">
        					<div class="editlabel fixedwidthlabel ">Group ID&nbsp;</div>
        					<div class="editvalue"><input id="auxgroup" name="auxgroup" class="textinput" style="width: 174px;" type="text" value="#auxgroupid#" onBlur="checkAuxGroup(); return true;" /></div>
        				</div>
        			</div>
        			<div class="searchbox">
            			<div class="editleftcolumn widecolumn">
            				<div class="editlabel fixedwidthlabel clearleft singleline">Broader&nbsp;</div>
            				<div class="editvalue"><input id="broader" name="broader" class="textinput inputfieldextended #broadercategorycolor#" type="text" value="#broadercategory#"
                                onBlur ="javascript:checkBroader(); return true;" /></div>
                            <div class="editlabel fixedwidthlabel clearleft singleline">Derived From&nbsp;</div>
            				<div class="editvalue "><input id="derivedfrom" name="derivedfrom" class="textinput inputfieldextended #derivedfromcolor#" type="text" value="#derivedfrom#"
                                onBlur ="javascript:checkDerivedFrom(); return true;" /></div>
            			</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel singleline"><span style="color: #9a9a9a;">110</span> Scope Note</div>
        					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue #scopenotecolor#" name="scopenote" rows="3">#scopenote#</textarea></div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel singleline"><span style="color: #9a9a9a;">111</span> App Note</div>
        					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue #appnotecolor#" name="appnote" rows="3">#appnote#</textarea></div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel singleline"><span style="color: #9a9a9a;">114</span> Info Note</div>
        					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue #informationnotecolor#" id="informationnote" name="informationnote" rows="2">#infonote#</textarea></div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel singleline"><span style="color: #9a9a9a;">125</span> Refs</div>
        					<div class="editvalue widevalue" id="references">#references#</div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel singleline"><span style="color: #9a9a9a;">115</span> Examples</div>
        					<div class="editvalue widevalue" id="examples">#examples#</div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel singleline">Parallel Div Instructions</div>
        					<div class="editvalue widevalue" id="pardivinst">#pardivinst#</div>
        				</div>
        			</div>
        		</div>

        		<div id="page2" #page2access# >
        			<div class="searchbox">
        				<div class="editleftcolumn">
        					<div class="editlabel fixedwidthlabel">Intro Date</div><div class="editvalue"><input id="introdate" name="introdate" class="textinput inputfield" type="text" value="#introdate#" /></div>
        				</div>
        				<div class="editrightcolumn rightalign">
        					<div class="editlabel fixedwidthlabel">Source&nbsp;</div><div class="editvalue"><input id="introsource" name="introsource" class="textinput inputfield" type="text" value="#introsource#" /></div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel">Intro Comment</div>
        					<div class="editvalue widevalue"><input id="introcomment" name="introcomment" class="textinput inputfieldextended" type="text" value="#introcomment#" /></div>
        				</div>
        				<div class="editleftcolumn">
        					<div class="editlabel fixedwidthlabel">Last Rev Date</div>
                            <div class="editvalue"><input id="lastrevdate" name="lastrevdate" class="textinput inputfieldshort" type="text" value="#lastrevdate#" /></div>
        					<div class="editlabel shortfixedwidthlabel rightalign">Fields&nbsp;</div>
        					<div class="editvalue"><input id="lastrevfields" name="lastrevfields" class="textinput" style="width:138px;" type="text" value="#lastrevfields#" /></div>
        					<div class="editlabel shortfixedwidthlabel rightalign">Source&nbsp;</div>
                            <div class="editvalue">
                                <input id="lastrevsource" name="lastrevsource" class="textinput inputfieldshort" type="text" value="#lastrevsource#" />
                            </div>
                        </div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel clearleft fixedwidthlabel">Rev Comment</div>
        					<div class="editvalue widevalue"><input id="lastrevcomment" name="lastrevcomment" class="textinput inputfieldextended" type="text" value="#lastrevcomment#" /></div>
        				</div>
        				<div class="editleftcolumn">
                            <div class="editlabel fixedwidthlabel">&nbsp;</div>
                            <div class="editvalue noborder">
                                <div class="edittextarea wideeditvalue revcopy">#lastrevcopy#</div>
                            </div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel">Revision Hist</div>
        					<div class="editvalue widevalue" id="revisions">#revisions#<a href="#" onMouseDown="javascript:addRevision();">Add</a></div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel">Editorial Note</div>
        					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue" name="editorialnote" rows="1">#editorialnote#</textarea></div>
        				</div>
        				<div class="editleftcolumn widecolumn">
        					<div class="editlabel fixedwidthlabel">Special Chars</div>
        					<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue" name="usespecialchars" rows="1">#usespecialchars#</textarea></div>
        				</div>
        			</div>
        		</div>
        		<div id="page3" #page3access# >
        			<div class="searchbox">
        				<div class="editleftcolumn">
        					<a name="keywordinput"></a>
        					<div class="editlabel inputeditlabel fixedwidthlabel">Scanned Terms&nbsp;</div>
        					<div class="editvalue">
        						<textarea class="edittextarea leftarea" id="keywords" name="keywords" rows="10"></textarea>&nbsp;
        						<a href="#keywordinput" title="Extract keywords from Caption and Verbal Examples" onmousedown="javascript:scansearchterms();">Scan</a>
        					</div>
        				</div>
                        <div class="editleftcolumn">
        					<a name="keywordlist"></a>
        					<div class="editlabel inputeditlabel fixedwidthlabel">Approved Keywords&nbsp;</div>
        					<div class="editvalue">
        						<textarea class="edittextarea leftarea" id="approvedkeywords" name="approvedkeywords" rows="10">#approvedkeywords#</textarea>
        					</div>
        				</div>
        				<div class="editrightcolumn">
        					<a name="alphaindex"></a>
        					<div class="editlabel inputeditlabel fixedwidthlabel">Alphabetical Index&nbsp;</div>
        					<div class="editvalue">
        						<textarea class="edittextarea leftarea" id="alphabeticalindex" name="alphabeticalindex"rows="10">#alphaindex#</textarea>&nbsp;
        					</div>
        				</div>
        			</div>
        		</div>
        		<div class="searchbox">
                    <div class="editleftcolumn widecolumn">
                		<div class="editlabel fixedwidthlabel" style="#showothercomments#">#editcommentslabel1#</div>
                        <div class="editvalue widevalue noborder" style="#showothercomments#">#othereditcomments#</div>
                		<div class="editlabel fixedwidthlabel">#editcommentslabel2#</div>
                		<div class="editvalue widevalue"><textarea class="edittextarea wideeditvalue" id="editcomments" name="editcomments" rows="1">#editcomments#</textarea></div>
                	</div>
        		</div>
        		<div class="searchbox" style="#showmrfdiffs#">
                    <div class="editleftcolumn widecolumn">
    					<div class="editlabel fixedwidthlabel singleline"><span style="color: magenta;">Fields different from MRF</span></div>
    					<div class="editvalue widevalue" id="examples">
                            <table class="reftable" width="100%" bgcolor="#cccccc" border="0" cellpadding="2" cellspacing="1">
                                <tr>
                                    <td width="50%" bgcolor="white" valign="top"><input type="checkbox" name="changed_including" id="changed_including" value="105" #changed_including#> Including</td>
                                    <td width="50%" bgcolor="white" valign="top"><input type="checkbox" name="changed_scopenote" id="changed_scopenote" value="110" #changed_scopenote#> Scope Note</td>
                                </tr>
                                <tr>
                                    <td width="50%" bgcolor="white" valign="top"><input type="checkbox" name="changed_appnote" id="changed_appnote" value="111" #changed_appnote#> Application Note</td>
                                    <td width="50%" bgcolor="white" valign="top"><input type="checkbox" name="changed_references" id="changed_references" value="115" #changed_references#> References</td>
                                </tr>
                                <tr>
                                    <td width="50%" bgcolor="white" valign="top"><input type="checkbox" name="changed_examples" id="changed_examples" value="125" #changed_examples#> Examples</td>
                                    <td width="50%" bgcolor="white" valign="top"><input type="checkbox" name="changed_editorial_note" id="changed_editorial_note" value="957" #changed_editorial_note#> Editorial Note</td>
                                </tr>
                            </table>
                        </div>
                	</div>
        		</div>
   				<div style="float: right; line-height: 32px; vertical-align: middle; text-align: right;">
 					&nbsp;
					<input type="submit" id="SubmitSave" name="SubmitSave" value="Save"/>
					<input type="submit" id="EditCancel" name="EditCancel" value="Cancel" onclick="javascript:CancelEditForm(); return false;"/>
				</div>
            </div>
       	</form>
    </div>
</body>
</html>
<script type="text/javascript">
function init()
{
    var hidScroll = document.getElementById('scrollvalue');
    document.getElementById('searchresultsdiv').scrollTop = hidScroll.value;
}
window.onload = init;
</script>

