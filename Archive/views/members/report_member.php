<? global $Profile, $User; ?>

<div id="report_response" style="display:none">
  You have succesfully reported this profile. We are looking into it.
  <br><br>
  <a href="user">Return to dashboard</a>
</div>

<table id="report_form">
  <tr>
    <td colspan=2 class="text">Please let us know why you are reporting this member:</td>
  </tr>
  <tr>
    <td colspan=2 class="opt"><input type="checkbox" name="report_spam" id="report_spam">Spam</td>
  </tr>
  <tr>
    <td colspan=2 class="opt"><input type="checkbox" name="report_abuse" id="report_abuse">Rude and Abusive</td>
  </tr>
  <tr>
    <td colspan=2 class="opt"><input type="checkbox" name="report_obscene" id="report_obscene">Obscenity or Inappropriate Content</td>
  </tr>
  <tr>
    <td colspan=2 class="opt"><input type="checkbox" name="report_copyright" id="report_copyright">Copyright Violation</td>
  </tr>
  <tr>
    <td colspan=2 class="opt"><input type="checkbox" name="report_hate" id="report_hate">Hate</td>
  </tr>
  <tr>
    <td colspan=2 class="opt"><input type="checkbox" name="report_other" id="report_other">Other</td>
  </tr>
  <tr>
    <td colspan=2 class="note">
      Please be assured that this report is strictly confidential. However if
      you are reporting other members or groups repeatedly and with malicious
      intent, your account may be suspended.
      <br><br>
      If you own the copyright for materials presented here and would like them
      removed, please see our instructions for notification of
      <a href="pages/copyright">copyright infringement</a>.
    </td>
  </tr>
  <tr>
    <td>
      Please provide details
    </td>
    <th id="report_count">140</th>
  </tr>
  <tr>
    <td class="reporttext" colspan=2>
      <div class="input">
        <textarea name="report_text" id="report_text" onkeyup="return Profile.ReportMember.Count(event,this,'report_count');"></textarea>
      </div>
    </td>
  </tr>
  <tr>
    <td class="submit" colspan=2>
      <input type="submit" name="cancel" id="cancel" value="Cancel" onclick="return Profile.Navigate('user_timeline_sent');">
      <input type="submit" name="report_submit" id="report_submit" value="Submit Report" onclick="return Profile.ReportMember.FormSubmit();">
    </td>
  </tr>
</table>
