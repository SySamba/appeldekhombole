# Instructions de Configuration - Appel de Khombole

## Étapes pour accéder à votre site via "appeldekhombole.local"

### 1. Configurer le Virtual Host dans XAMPP

1. Ouvrez le fichier de configuration Apache :
   - Chemin : `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
   
2. Ajoutez le contenu du fichier `appeldekhombole.conf` à la fin du fichier `httpd-vhosts.conf`

   Ou copiez directement ce code :
   ```apache
   <VirtualHost *:80>
       ServerName appeldekhombole.local
       ServerAlias www.appeldekhombole.local
       DocumentRoot "C:/xampp/htdocs/carte-membre"
       
       <Directory "C:/xampp/htdocs/carte-membre">
           Options Indexes FollowSymLinks MultiViews
           AllowOverride All
           Require all granted
       </Directory>
       
       ErrorLog "logs/appeldekhombole-error.log"
       CustomLog "logs/appeldekhombole-access.log" common
   </VirtualHost>
   ```

### 2. Modifier le fichier hosts de Windows

1. Ouvrez le Bloc-notes en tant qu'**Administrateur**
   - Clic droit sur "Bloc-notes" → "Exécuter en tant qu'administrateur"

2. Ouvrez le fichier hosts :
   - Chemin : `C:\Windows\System32\drivers\etc\hosts`

3. Ajoutez cette ligne à la fin du fichier :
   ```
   127.0.0.1    appeldekhombole.local
   127.0.0.1    www.appeldekhombole.local
   ```

4. Sauvegardez le fichier

### 3. Redémarrer Apache dans XAMPP

1. Ouvrez le panneau de contrôle XAMPP
2. Arrêtez Apache (bouton "Stop")
3. Redémarrez Apache (bouton "Start")

### 4. Accéder à votre site

Ouvrez votre navigateur et tapez :
- `http://appeldekhombole.local`
- ou `http://www.appeldekhombole.local`

Votre site sera maintenant accessible ! 🎉

---

## Vérification

Si vous rencontrez des problèmes :

1. Vérifiez que Apache est bien démarré dans XAMPP
2. Vérifiez que le fichier hosts a bien été modifié
3. Videz le cache DNS de Windows avec cette commande (dans PowerShell en admin) :
   ```
   ipconfig /flushdns
   ```
4. Redémarrez votre navigateur

## Alternative : Utiliser "appeldekhombole" sans ".local"

Si vous préférez utiliser simplement "appeldekhombole" :

1. Dans le fichier hosts, utilisez :
   ```
   127.0.0.1    appeldekhombole
   ```

2. Dans le fichier httpd-vhosts.conf, changez :
   ```apache
   ServerName appeldekhombole
   ServerAlias www.appeldekhombole
   ```

3. Accédez au site via : `http://appeldekhombole`
