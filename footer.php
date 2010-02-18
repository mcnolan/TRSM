<?
/*
| Trek RPG Site Manager
| Author: Nolanator
| Version: 1.0
|
| File: footer.php
| Build 3
| <Changes>
| Css Changes
| TRSM credit updated
|
| <Purpose>
| Footer file, finishes off the page theme, closes the database connections.
|
| TRSM1.0 is (c) Nolan 2003-2005, and is covered by the GPL Licence (See gpl.txt for more information)
*/
?>
        </td>
    </tr>
    <tr height="15">
        <td class="credits"><script language="JavaScript">unScramble("trsm","mrjohn.msshost.com","","TRSM","Click To Email TRSMs author");</script><img src="<? echo $imagepath; ?>trsm_logo_small.gif" border="0"></a></td>
    </tr>
    </table>
    
    </td>
</tr>
</table>

</body>
</html>
<?
// This just closes any open SQL connections, to be tidy
$sql->close();
?>