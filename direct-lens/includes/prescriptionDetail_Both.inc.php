       <tr >
                <td bgcolor="#D7E1FF" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">Sphere</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">Cylinder</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">Axis</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">Addition</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">Prism</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">Thickness</td>
              </tr>
              <tr >
                <td align="right" class="formCellNosidesRA">R.E.</td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['RE_SPH_NUM'].$_SESSION['PrescrData']['RE_SPH_DEC'];?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['RE_CYL_NUM'].$_SESSION['PrescrData']['RE_CYL_DEC'];?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['RE_AXIS'];?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['RE_ADD'];?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['RE_PR_AX']."&nbsp;".$_SESSION['PrescrData']['RE_PR_IO']."&nbsp;&nbsp;".$_SESSION['PrescrData']['RE_PR_AX2']."&nbsp;".$_SESSION['PrescrData']['RE_PR_UD'];?></td>
 <td align="center" class="formCellNosides">
<?php if ($_SESSION['PrescrData']['RE_CT'] <> '') echo $_SESSION['PrescrData']['RE_CT'] . ' CT'; elseif ($_SESSION['PrescrData']['RE_ET'] <> '')  echo $_SESSION['PrescrData']['RE_ET'] . ' ET'  ?></td>
             
          	 	 	    
             
              </tr>
			  <tr >
                <td align="right" class="formCellNosidesRA">
                  L.E.</td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['LE_SPH_NUM'].$_SESSION['PrescrData']['LE_SPH_DEC'];?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['LE_CYL_NUM'].$_SESSION['PrescrData']['LE_CYL_DEC'];?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['LE_AXIS'];?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['LE_ADD'];?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['LE_PR_AX']."&nbsp;".$_SESSION['PrescrData']['LE_PR_IO']."&nbsp;&nbsp;".$_SESSION['PrescrData']['LE_PR_AX2']."&nbsp;".$_SESSION['PrescrData']['LE_PR_UD'];?></td>
 <td align="center" class="formCellNosides">
<?php if ($_SESSION['PrescrData']['LE_CT'] <> '') echo $_SESSION['PrescrData']['LE_CT'] . ' CT'; elseif ($_SESSION['PrescrData']['LE_ET'] <> '')  echo $_SESSION['PrescrData']['LE_ET'] . ' ET'  ?></td>
              </tr>
              <tr >
                <td align="right" bgcolor="#D7E1FF" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">Dist. PD </td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">Near PD </td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">Height</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">&nbsp;</td>
                 <td align="center" bgcolor="#D7E1FF" class="formCellNosides">&nbsp;</td>
              </tr>
              <tr >
                <td align="right" class="formCellNosidesRA">R.E.</td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['RE_PD'];?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['RE_PD_NEAR'];?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['RE_HEIGHT'];?></td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
              </tr>
              <tr >
                <td align="right" class="formCellNosidesRA"> L.E.</td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['LE_PD'];?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['LE_PD_NEAR'];?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['LE_HEIGHT'];?></td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
              </tr>
