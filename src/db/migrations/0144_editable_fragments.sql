CREATE SEQUENCE seq_editable_fragments;
CREATE TABLE editable_fragments (
	id INTEGER PRIMARY KEY DEFAULT NEXTVAL('seq_editable_fragments'),
	key VARCHAR(255) NOT NULL,
	content_section VARCHAR(255), -- sem se ulozi neco jako 'obsah/vzdelavani/kalendar-akci'
	content_type VARCHAR(255) NOT NULL, -- 'text', 'html', 'markdown', 'date' , 'Person'
	lang CHAR(2), -- u objeku (Person) bude null
	initial_content TEXT,
	content TEXT,
	--
	-- nepotrebujeme pole created_by_user_id; zaznamy budou vznikat automaticky (bez vedomi nekoho)
	updated_by_user_id INT,
	--
	created_at TIMESTAMP NOT NULL DEFAULT NOW(),
	updated_at TIMESTAMP,
	--
	CONSTRAINT fk_editablefragments_upd_users FOREIGN KEY (updated_by_user_id) REFERENCES users
);
CREATE UNIQUE INDEX unq_editablefragments ON editable_fragments (key,content_type,COALESCE(lang,''));

CREATE SEQUENCE seq_editable_fragment_history;
CREATE TABLE editable_fragment_history (
	id INTEGER PRIMARY KEY DEFAULT NEXTVAL('seq_editable_fragments'),
	--
	editable_fragment_id INT NOT NULL,
	content TEXT,
	comment VARCHAR(255),
	--
	created_by_user_id INT NOT NULL,
	--
	created_at TIMESTAMP,
	--
	CONSTRAINT fk_editablefragmenthistory_cr_users FOREIGN KEY (created_by_user_id) REFERENCES users
);
CREATE INDEX in_editablefragmenthistory_editablefragmentid ON editable_fragment_history(editable_fragment_id);
