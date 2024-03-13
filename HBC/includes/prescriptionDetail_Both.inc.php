       <tr >
                <td bgcolor="#D5EEF7" class="formCellNosides">&nbsp;</td>
              <td align="center" bgcolor="#D5EEF7" class="formCellNosides">
              <?php  if ($mylang == 'lang_french'){ 
			  echo  'Sph&egrave;re';
			  }else{
			  echo  'Sphere';
			  }
			  ?>
             
              </td>
                <td align="center" bgcolor="#D5EEF7" class="formCellNosides">
                 <?php  if ($mylang == 'lang_french'){ 
			  echo  'Cylindre';
			  }else{
			  echo  'Cylinder';
			  }
			  ?>
                </td>
                <td align="center" bgcolor="#D5EEF7" class="formCellNosides">
               <?php  if ($mylang == 'lang_french'){ 
			  echo  'Axes';
			  }else{
			  echo  'Axis';
			  }
			  ?></td>
                <td align="center" bgcolor="#D5EEF7" class="formCellNosides">
                <?php  if ($mylang == 'lang_french'){ 
			  echo  'Addition';
			  }else{
			  echo  'Addition';
			  }
			  ?>
                </td>
                <td align="center" bgcolor="#D5EEF7" class="formCellNosides"> 
				<?php  if ($mylang == 'lang_french'){ 
			  echo  'Prismes';
			  }else{
			  echo  'Prism';
			  }
			  ?></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosidesRA">OD</td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['RE_SPHERE'];?> <?php //echo '<b>Converti : '. $NewSphereDroit. '</b>'; ?></td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['RE_CYL'];?> <?php //echo '<b>Converti : '. $NewCylDroit. '</b>'; ?></td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['RE_AXIS'];?> <?php //echo '<b>Converti : '. $NewAxeDroit. '</b>'; ?></td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['RE_ADD'];?></td>
                <td align="center" class="formCellNosides">
				<?php 
				if ($_SESSION['PrescrData']['RE_PR_AX'] <> 'None') echo $_SESSION['PrescrData']['RE_PR_AX'];
				echo "&nbsp;";
				if ($_SESSION['PrescrData']['RE_PR_IO'] <> 'None') echo $_SESSION['PrescrData']['RE_PR_IO'];
				echo "&nbsp;&nbsp;";
				if ($_SESSION['PrescrData']['RE_PR_AX2'] <> 'None') echo $_SESSION['PrescrData']['RE_PR_AX2'];
				echo "&nbsp;";
				if ($_SESSION['PrescrData']['RE_PR_UD'] <> 'None') echo $_SESSION['PrescrData']['RE_PR_UD'];
				?>
</td>
              </tr>
			  <tr >
                <td align="right" class="formCellNosidesRA">
                  OG</td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['LE_SPHERE'];?> <?php //echo '<b>Converti : '. $NewSphereGauche. '</b>'; ?> </td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['LE_CYL'];?> <?php //echo '<b>Converti : '. $NewCylGauche . '</b>'; ?> </td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['LE_AXIS'];?> <?php //echo '<b>Converti : '. $NewAxeGauche . '</b>'; ?> </td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['LE_ADD'];?></td>
                <td align="center" class="formCellNosides">
				<?php
				if ($_SESSION['PrescrData']['LE_PR_AX'] <> 'None') echo $_SESSION['PrescrData']['LE_PR_AX'];
				echo "&nbsp;";
				if ($_SESSION['PrescrData']['LE_PR_IO'] <> 'None') echo $_SESSION['PrescrData']['LE_PR_IO'];
				echo "&nbsp;&nbsp;";
				if ($_SESSION['PrescrData']['LE_PR_AX2'] <> 'None') echo $_SESSION['PrescrData']['LE_PR_AX2'];
				echo "&nbsp;";
				if ($_SESSION['PrescrData']['LE_PR_UD'] <> 'None') echo $_SESSION['PrescrData']['LE_PR_UD'];
				?></td>
              </tr>
              <tr >
               <td align="center" bgcolor="#D5EEF7" class="formCellNosides">&nbsp;</td>
                <td align="left" bgcolor="#D5EEF7" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#D5EEF7" class="formCellNosides">PD</td>
                <td align="center" bgcolor="#D5EEF7" class="formCellNosides">
                <?php  if ($mylang == 'lang_french'){ 
			  echo  'Hauteur';
			  }else{
			  echo  'Height';
			  }
			  ?></td>
                <td align="center" bgcolor="#D5EEF7" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#D5EEF7" class="formCellNosides">&nbsp;</td>
               
              </tr>
              <tr >
                <td align="right" class="formCellNosidesRA">OD</td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['RE_PD'];?></td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['RE_PD_NEAR'];?></td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['RE_HEIGHT'];?></td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
              </tr>
              <tr >
                <td align="right" class="formCellNosidesRA"> OG</td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['LE_PD'];?></td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['LE_PD_NEAR'];?></td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['LE_HEIGHT'];?></td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
              </tr>
