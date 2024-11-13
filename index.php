<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<pre>';  // Formats the output in a readable way
    print_r($_POST);  // Prints all POST data
    echo '</pre>';
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, user-scalable=0">
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8">
    <title>CODENIM</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
    <script src="https://unpkg.com/react@18/umd/react.development.js"></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/fontawesome.min.css" integrity="sha384-NvKbDTEnL+A8F/AA5Tc5kmMLSJHUO868P+lDtTpJIeQdGYaUIuLr4lVGOEA1OcMy" crossorigin="anonymous">
    <style>
        body {
            background: #EEEEEE;
            background-size: cover;
        }
        .custom-file-input:lang(en) ~ .custom-file-label::after {
            content: "Parcourir"; /* Custom text */
        }
    </style>
</head>
<body>
    <div id="root"></div>
    <script type="text/babel">
        const sizes = ['24', '25', '26', '27', '28', '29', '30', '31', '32', 'XXS', 'XS', 'S', 'M', 'L', 'XL'];
        const reasons = ['Erreur de livraison', 'Trou', 'Tâches', 'Déchiré', 'Boutons', 'Défaut de fabrication', 'Autre'];

        function InputField({ label, id, type = 'text', required = false }) {
            return (
                <div className="form-group">
                    <input
                        type={type}
                        className="form-control"
                        id={id}
                        name={id}
                        placeholder={`${label}${required ? ' *' : ''}`}
                        required={required}
                    />
                </div>
            );
        }

        function SelectField({ label, id, options, required = false }) {
            return (
                <div className="form-group">
                    <select className="form-control" id={id} name={id} required={required}>
                        <option value="">{`${label}${required ? ' *' : ''}`}</option>
                        {options.map(option => (
                            <option key={option} value={option}>{option}</option>
                        ))}
                    </select>
                </div>
            );
        }

        function RadioField({ label, id, options, required = false, onChange }) {
            return (
                <div className="form-group">
                    <label>{`${label}${required ? ' *' : ''}`}</label>
                    <div>
                        {options.map((option, index) => (
                            <div className="form-check form-check-inline" key={index}>
                                <input
                                    className="form-check-input"
                                    type="radio"
                                    name={id}
                                    id={`${id}-${option.value}`}
                                    value={option.value}
                                    required={required}
                                    onChange={onChange}
                                />
                                <label className="form-check-label" htmlFor={`${id}-${option.value}`}>
                                    {option.label}
                                </label>
                            </div>
                        ))}
                    </div>
                </div>
            );
        }

        function ImageUploadField({ label, id }) {
            const [preview, setPreview] = React.useState(null);
            const [fileName, setFileName] = React.useState('');

            const handleImageChange = (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onloadend = () => setPreview(reader.result);
                    reader.readAsDataURL(file);
                    setFileName(file.name); // Set the filename to display in the label
                }
            };

            return (
                <div className="form-group">
                    <div className="input-group">
                        <div className="input-group-prepend">
                            <span className="input-group-text" id={id}>{label}</span>
                        </div>
                        <div className="custom-file">
                            <input 
                                type="file" 
                                className="custom-file-input" 
                                id={id}
                                name={id}
                                accept="image/*"
                                onChange={handleImageChange}
                                aria-describedby={id}
                            />
                            <label className="custom-file-label text-truncate" htmlFor={id}>{fileName || 'Choisir une image'}</label>
                        </div>
                    </div>

                    {preview && (
                        <div className="mt-3">
                            <img src={preview} alt="Image Preview" style={{ maxWidth: '100%', height: 'auto' }} />
                        </div>
                    )}
                </div>
            );
        }

        function ProductReturnSection({ index, onAnotherReturnChange }) {
            return (
                <div>
                    <h5>Produit {index + 1}</h5>
                    <InputField label="Référence du produit" id={`ref-${index}`} required />
                    <SelectField label="Taille" id={`taille-${index}`} options={sizes} required />
                    <InputField label="Couleur" id={`couleur-${index}`} required />
                    <SelectField label="Choisissez la raison du retour" id={`raison-${index}`} options={reasons} required />
                    <ImageUploadField label="Photo par défaut" id={`imageUpload1-${index}`} />
                    <ImageUploadField label="Autre photo" id={`imageUpload2-${index}`} />
                    <RadioField
                        label="S'agit-il du pack complet ?"
                        id={`completePackage-${index}`}
                        options={[{ label: 'Oui', value: 'yes' }, { label: 'Non', value: 'no' }]}
                        required
                    />
                    <div className="form-group">
                        <textarea className="form-control" id={`commentaire-${index}`} name={`commentaire-${index}`} placeholder="Commentaire" rows="5"></textarea>
                    </div>

                    {/* Question: Avez-vous un autre retour ? */}
                    <RadioField
                        label="Avez-vous un autre retour ?"
                        id={`anotherReturn-${index}`}
                        options={[{ label: 'Oui', value: 'yes' }, { label: 'Non', value: 'no' }]}
                        onChange={onAnotherReturnChange}
                        required
                    />
                </div>
            );
        }

        function ReturnForm() {
            const [productReturns, setProductReturns] = React.useState([{ index: 0 }]);
            const [hasAnotherReturn, setHasAnotherReturn] = React.useState('no'); // Tracks the user's response to "Avez-vous un autre retour ?"

            const addReturnSection = () => {
                setProductReturns([...productReturns, { index: productReturns.length }]);
            };

            const removeReturnSectionsAfterIndex = (index) => {
                // Remove the product return section and all sections with a larger index
                setProductReturns(productReturns.filter(product => product.index <= index));
            };

            const handleAnotherReturnChange = (e) => {
                const value = e.target.value;
                const currentProductIndex = parseInt(e.target.id.split('-')[1]);

                setHasAnotherReturn(value);

                if (value === 'yes') {
                    addReturnSection(); // Add a new product return section if the user answers "Oui"
                } else if (value === 'no') {
                    removeReturnSectionsAfterIndex(currentProductIndex); // Remove current product and all products with a larger index
                }
            };

            const handleSubmit = (e) => {
                e.preventDefault();
                // alert('Form submitted');
            };

            return (
                <div className="container my-4">
                    <h2 className="text-center">Formulaire de retour</h2>
                    <br/>
                    <form onSubmit={handleSubmit} method="post">
                        <InputField label="Société" id="societe" required />
                        <div className="form-row">
                            <div className="col-12 col-sm-6">
                                <InputField label="Prénom" id="prenom" required />
                            </div>
                            <div className="col-12 col-sm-6">
                                <InputField label="Nom" id="nom" required />
                            </div>
                        </div>
                        <InputField label="Adresse" id="adresse" />
                        <div className="form-row">
                            <div className="col-12 col-sm-6">
                                <InputField label="Ville" id="ville" />
                            </div>
                            <div className="col-12 col-sm-6">
                                <InputField label="Code Postal" id="cp" />
                            </div>
                        </div>
                        <InputField label="Pays" id="pays" />
                        <InputField label="Téléphone ou Portable" id="mobile" type="tel" required />
                        <InputField label="Email" id="email" type="email" required />
                        <InputField label="Numéro de facture ou de commande" id="nfacture" />

                        {/* Render the product return sections */}
                        {productReturns.map((_, index) => (
                            <ProductReturnSection 
                                key={index} 
                                index={index} 
                                onAnotherReturnChange={handleAnotherReturnChange} 
                            />
                        ))}

                        <div className="form-group text-right">
                            <button type="submit" className="btn btn-primary">Envoyer</button>
                        </div>
                    </form>
                </div>
            );
        }

        function App() {
            return <ReturnForm />;
        }

        ReactDOM.createRoot(document.getElementById('root')).render(<App />);
    </script>
</body>
</html>
