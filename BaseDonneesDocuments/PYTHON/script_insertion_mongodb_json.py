import json
from pymongo import MongoClient
from os import listdir
from os.path import isfile, join


# Connexion à la base de données
client = MongoClient('localhost', 27017)
db = client.BD_TrainIDF  # Nom de la base de données
collectionTrips = db.collectionTrips  # Nom de la collection
collectionRoutes = db.collectionRoutes  # Nom de la collection

path = '../DATA/'
ficCL = 'collectionLignes.json'

listeFichiers = listdir(path)

for fic in listeFichiers:
    data = None
    print(join(path, fic))

    # Charger le fichier JSON
    with open(join(path, fic)) as file:
        data = json.load(file)

    if fic == ficCL:
        # Insérer les données dans la collection
        collectionRoutes.insert_many(data)
    else :
        # Insérer les données dans la collection
        collectionTrips.insert_many(data)