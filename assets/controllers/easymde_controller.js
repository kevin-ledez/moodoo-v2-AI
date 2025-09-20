
import { Controller } from '@hotwired/stimulus';
import marked from 'marked';
import EasyMDE from 'easymde';

// Import the CSS for EasyMDE. AssetMapper will handle this.
import 'easymde/dist/easymde.min.css';

export default class extends Controller {
  connect() {
    // Make marked available globally for EasyMDE
    window.marked = marked;

    this.editor = new EasyMDE({
      element: this.element,
      spellChecker: false, // Désactive le correcteur orthographique par défaut
      // Vous pouvez ajouter d'autres options ici
      // voir la documentation de EasyMDE
    });
  }

  disconnect() {
    // Détruit l'instance pour éviter les fuites de mémoire
    if (this.editor) {
      this.editor.toTextArea();
      this.editor = null;
    }
    // Clean up the global scope
    delete window.marked;
  }
}
