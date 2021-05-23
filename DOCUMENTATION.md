# Epignosis Assessment Documentation

## Installation

Με χρήση docker η εγκατάσταση είναι εξαιρετικά απλή:

Αρχικά κάνουμε clone to repo από το github, αντιγράφουμε το configuration από το `.sample.env`
και βάσει αυτού, ενημερώνουμε τις παραμέτρους στο `.env`. Κατ' ελάχιστο θα πρέπει να ρυθμίσετε τα smtp server settings.

```shell
git clone https://github.com/mistericy/rnr
cd rnr
cp .env.sample .env
```

Αφού συμπληρωθούν οι κατάλληλες τιμές στο `.env`, αρκεί να τρέξετε:

```shell
docker-compose up
```

### Without docker

Στην περίπτωση που δεν θέλετε να χρησιμοποιήσετε docker, θα πρέπει να στηθεί ένα LEMP stack - δεν προσφέρεται υποστήριξη
για Apache Server και το Makefile θα βοηθήσει πολύ με τα initialization commands. Προφανώς υποστηρίζονται και άλλα OS,
but you are on your own.

### Stack

#### MariaDB / MySQL

To initialization script της βάσης βρίσκεται στον κατάλογο `docker/database/init`. Να σημειωθεί ότι πριν το τρέξετε θα
πρέπει να έχετε δημιουργήσει database με το όνομα `rnr`.

#### Nginx

Το configuration του server βρίσκεται στον κατάλογο `docker/nginx/sites-enabled`. Προφανώς θα πρέπει να αλλάξετε
το `fastcgi_pass` ανάλογα με το σύστημά σας.

#### PHP-FPM

Τα extensions `pdo` & `pdo_mysql` είναι απαραίτητα για την εκτέλεση του project.

### Initialization

Αφού κάνετε τις απαραίτητες ρυθμίσεις στο stack, θα πρέπει να γίνουν τα παρακάτω:

1. Εγκατάσταση των dependencies του backend (είναι απαραίτητο να υπάρχει εγκατεστημένο το Composer στο σύστημά σας):

```shell
composer install 
```

2. Εγκατάσταση των dependencies του frontend (είναι απαραίτητο να υπάρχει εγκατεστημένο το npm στο σύστημά σας):

```shell
cd frontend 
npm intall
```

3. Building the frontend

```shell
npm run build
```

## Running

Με χρήση docker, αφού ανέβουν όλα τα containers, αρκεί να μεταβείτε στην διεύθυνση
[http://localhost:3333/build](http://localhost:3333/build). H πόρτα έχει επιλεχθεί ώστε να μην υπάρχουν conflicts με
άλλες εφαρμογές που τρέχουν στο συστημα.

Χωρίς χρηση docker, θα πρέπει να γνωρίζετε πού τρέχει η εφαρμογή (hostname & port). Σε αυτό προσθέτουμε το path `/build`
ώστε να τρέξει το frontend.

## Developer's Choices

### Doctrine & Serializer

Καθώς η υλοποίηση δεν απαιτεί εξειδικευμένα ερωτήματα SQL (unions, nested selects, transactions, etc), επιλέχθηκε το
Doctrine ως βιβλιοθήκη που θα αναλάβει τον ρόλο του μεσάζοντα με την βάση δεδομένων. Και γιατί με τη χρήση του μπορούμε
να παράγουμε εξαιρετικά καθαρό κώδικα - κάθε Entity είναι μια συγκεκριμένη κλάση, με συγκεκριμένα properties που
αντιστοιχίζονται με table columns - αλλά και γιατί έτσι κατάφερε να μειωθεί το development time δραματικά.

Το μόνο εντελώς custom στοιχείο που χρησιμοποιείται, όσον αφορά το manipulation των δεδομένων, είναι η
κλάση [Persistence](./src/Persistence.php). Η συγκεκριμένη κλάση είναι μια εξαιρετικά scaled down version ενός service
που είχα γράψει πριν χρόνια, για να αναλάβει το ρόλο ενός agnostic injector of data σε αντικείμενα που διαχειρίζεται το
doctrine. Καθώς η πολυπλοκότητά της είναι μεγάλη, σχεδόν κάθε γραμμή έχει σχόλιο έτσι ώστε να είναι κατανοητή η
λειτουργία της.

Για τους ίδιους περίπου λόγους χρησιμοποιήθηκε και το `jms/serializer`. Ο τρόπος που κάνει serialize τα δεδομένα για να
τα περάσει στο frontend είναι αυτό που χρειάζεται η Persistence για να κάνει inject δεδομένα σε αντικείμενα, ώστε να
αποθηκευτούν στην βάση.

Παίρνοντας για παράδειγμα το [User](./src/Entity/User.php) class, o serializer επιστρέφει τις ιδιότητες 
του αντικειμένου που δεν έχουμε κάνει Exclude, με τις ονομασίες των properties της κλάσης:

```json
{
  "id": 2,
  "givenName": "Nikos",
  "familyName": "Koukos",
  "email": "n.koukos@gavgav.gr",
  "userType": {
    "id": 1,
    "type": "User",
    "isAdmin": false
  }
}
```

Το frontend θα πάρει το αντικείμενο και θα το αντιστοιχίσει στα κατάλληλα πεδία. 
Στην περίπτωση που ο χρήστης επιλέξει να κάνει αλλαγές στο αντικείμενο και να τις αποθηκεύσει, το 
αντικείμενο, ως έχει (στο frontend) θα γίνει μέρος του PUT request το οποίο θα σταλεί στο backend.

Αν, για παράδειγμα, θέλουμε να αλλάξουμε το email του χρήστη, αρκεί να στείλουμε ολόκληρο το 
αντικείμενο με το νέο email στο backend, όπου η `updateUser` του 
[UserController](./src/Controller/UserController.php) θα:

   1. Πάρει τα data από το HTTP Request (βλέπε `getData` στον [AbstractRestController](./src/Controller/AbstractRestController.php))
   2. Φορτώσει το αντικείμενο από την βάση δεδομένων
   3. Καλέσει την `persist` του [Persistence](./src/Persistence.php) για να καταχωρηθούν οι αλλαγές στο
αντικείμενο και εν τέλει στην βάση. 
   4. Επιστρέψει στον καλούντα το ενημερωμένο αντικείμενο.

Με αυτό τον τρόπο μπορούμε:
   1. Να κάνουμε το data validation στο frontend και να αφήσουμε το backend να αναλάβει το Security,
τα CRUD operations, την αποστολή email, δημιουργία αναφορών κλπ.
   2. Να απλοποιήσουμε το debugging γιατί τα ονόματα των ιδιοτήτων των αντικειμένων *δεν* αλλάζουν
ποτέ και πουθενά.
   3. Με χρήση reflection θα μπορούσαμε να δημιουργήσουμε τα απαραίτητα models για το frontend - αν δουλεύαμε με Angular2+


### JSON Web Tokens

## Extensibility

##
