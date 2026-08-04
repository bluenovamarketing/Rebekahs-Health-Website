/** Preserve Forminator's real submission while aligning its asynchronously rendered copy. */
document.addEventListener('DOMContentLoaded', () => {
	const alignCopy = () => {
	const form = document.querySelector('.forminator-custom-form-1064');
		if (!form) return;

	const textarea = form.querySelector('textarea[name="textarea-1"]');
	const button = form.querySelector('.forminator-button-submit');

		if (textarea && textarea.placeholder !== 'How can we help?') textarea.placeholder = 'How can we help?';
		if (button && button.textContent.trim() !== 'Send my message') button.textContent = 'Send my message';
	};

	alignCopy();
	const observer = new MutationObserver(alignCopy);
	observer.observe(document.querySelector('#message') || document.body, { childList: true, subtree: true });
	window.setTimeout(() => observer.disconnect(), 10000);
});
